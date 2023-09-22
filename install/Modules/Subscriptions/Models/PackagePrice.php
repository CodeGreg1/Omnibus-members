<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Modules\Carts\Models\PromoCode;
use Modules\Cashier\Facades\Cashier;
use Modules\Carts\Traits\Purchasable;
use Modules\Carts\Traits\Checkoutable;
use Illuminate\Database\Eloquent\Model;
use Modules\Subscriptions\Facades\Price;
use Camroncade\Timezone\Facades\Timezone;
use Modules\Subscriptions\Facades\Product;
use Modules\Carts\Contracts\PurchasableItem;
use Modules\Cashier\Traits\CashierModeScope;
use Modules\AvailableCurrencies\Facades\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackagePrice extends Model implements PurchasableItem
{
    use HasFactory, Purchasable, Checkoutable, CashierModeScope;

    protected $fillable = [
        "package_id",
        "package_term_id",
        "price",
        "compare_at_price",
        "type",
        "trial_days_count",
        "currency",
        "live"
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['term', 'package'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float'
    ];

    /**
     * Append modified field values
     */
    protected $appends = [
        'amount_display',
        'compare_at_price_display',
        'created',
        'user_amount_display',
        'user_compare_at_price_display',
    ];

    /**
     * Gateway integrations
     */
    public function gatewayIntegrations()
    {
        return $this->hasMany(PackagePriceIntegration::class, 'package_price_id', 'id');
    }

    /**
     * get the price pricing table items
     */
    public function pricingTableItem()
    {
        return $this->hasMany(PricingTableItem::class);
    }

    /**
     * Get formatted price.
     *
     * @param $value
     *
     * @return string
     */
    public function getAmountDisplayAttribute($value)
    {
        return currency_format($this->price, $this->currency);
    }

    /**
     * Get formatted compare_at_price.
     *
     * @param $value
     *
     * @return string
     */
    public function getCompareAtPriceDisplayAttribute($value)
    {
        return currency_format($this->compare_at_price, $this->currency);
    }

    /**
     * Get formatted price for the current user currency locale.
     *
     * @param $value
     *
     * @return string
     */
    public function getUserAmountDisplayAttribute($value)
    {
        $format = auth()->check() ? auth()->user()->currency : $this->currency;
        return currency($this->price, $this->currency, $format);
    }

    /**
     * Get formatted compare at price for the current user currency locale.
     *
     * @param $value
     *
     * @return string
     */
    public function getUserCompareAtPriceDisplayAttribute($value)
    {
        $format = auth()->check() ? auth()->user()->currency : $this->currency;
        return currency($this->compare_at_price, $this->currency, $format);
    }

    public function getCreatedAttribute($value)
    {
        return Carbon::parse(
            Timezone::convertFromUTC(
                $this->created_at,
                auth()->check() ? auth()->user()->timezone : config('app.timezone')
            )
        )->isoFormat('lll');
    }

    public function getBillingDescription()
    {
        if ($this->isRecurring()) {
            return $this->getRecurringBillingDescription();
        }

        return __('Lifetime subscription');
    }

    public function getRecurringBillingDescription()
    {
        $desc = '';
        $descInt = 'Every ' . $this->term->interval_count . ' ' . Str::plural($this->term->interval);
        switch ($this->term->interval) {
            case 'day':
                $desc = $this->term->interval_count === 1 ? 'Daily' : $descInt;
                break;
            case 'week':
                $desc = $this->term->interval_count === 1 ? 'Weekly' : $descInt;
                break;
            case 'month':
                $desc = $this->term->interval_count === 1 ? 'Monthly' : $descInt;
                break;
            case 'year':
                $desc = $this->term->interval_count === 1 ? 'Yearly' : $descInt;
                break;

            default:
                //
                break;
        }

        return $desc . ' billing';
    }

    public function getPriceDescription()
    {
        $string = $this->user_amount_display . ' ';
        if ($this->isRecurring()) {
            if ($this->term->interval_count > 1) {
                $string .= ' every ' . $this->term->interval_count . ' ' . Str::plural($this->term->interval, $this->term->interval_count);
            } else {
                $string .= ' / ' . $this->term->interval;
            }
        } else {
            $string .= 'forever';
        }

        return $string;
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function term()
    {
        return $this->belongsTo(PackageTerm::class, 'package_term_id');
    }

    public function isRecurring()
    {
        return $this->type === 'recurring';
    }

    public function subscriptions()
    {
        return $this->hasMany(SubscriptionItem::class);
    }

    /**
     * check if price has any subscriptions
     *
     * @return bool
     */
    public function hasSubscriptions()
    {
        return !!$this->subscriptions()->count();
    }

    /**
     * Get total subscriptions for this price
     *
     * @return int
     */
    public function subscriptionsCount()
    {
        return $this->subscriptions()->count();
    }

    /**
     * Get total active subscriptions for this price
     *
     * @return int
     */
    public function activeSubscriptionsCount()
    {
        return $this->subscriptions->map(function ($item) {
            return $item->whereHas('subscription', function ($query) {
                return $query->active();
            });
        })->count();
    }

    public function getNotEqual(array $attributes, $columns = [])
    {
        $columns = count($columns) ? $columns : $this->getFillable();

        $diff = [];
        foreach ($columns as $column) {
            if ($this->{$column} !== $attributes[$column]) {
                $diff[$column] = $this->{$column};
            }
        }

        return $diff;
    }

    public function getDiscountedPayload(PromoCode $code)
    {
        $discount = $code->coupon->amount;
        if ($code->coupon->amount_type === 'percentage') {
            $discount = ($discount / 100) * $this->price;
        }

        $newPrice = $this->price - $discount;

        return [
            'price' => $newPrice,
            'amount' => $discount
        ];
    }

    /**
     * The url path to the product view
     *
     * @return string
     */
    public function showPath()
    {
        return route('user.products.show', $this);
    }

    /**
     * the price
     *
     * @return float
     */
    public function cartPrice()
    {
        return $this->price;
    }

    /**
     * the currency
     *
     * @return float
     */
    public function cartCurrency()
    {
        return $this->currency;
    }

    /**
     * the items name
     *
     * @return string
     */
    public function cartItemName()
    {
        return $this->package->name;
    }

    /**
     * the items name
     *
     * @return string
     */
    public function cartItemDescription()
    {
        if ($this->isRecurring()) {
            return $this->term->description . ' Billing';
        }
        return __('Onetime Billing');
    }

    /**
     * the items preview image
     *
     * @return string
     */
    public function cartImage()
    {
        return null;
    }

    /**
     * check if item is shippable
     *
     * @return bool
     */
    public function cartItemShippable()
    {
        return false;
    }

    /**
     * check if item is stockable
     *
     * @return bool
     */
    public function isStockable()
    {
        return false;
    }

    /**
     * Get the unit price of the package price
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|int|float
     */
    public function getUnitPrice($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $price = number_format(currency(
            $this->price,
            $this->currency,
            $currency,
            false
        ), 2, '.', '');

        if (!$formatted) {
            return (float) $price;
        }

        return currency_format($price, $currency);
    }

    public function getGatewayId($gateway)
    {
        $integration = $this->gatewayIntegrations()->where('gateway', $gateway)->first();
        if ($integration) {
            return $integration->id;
        }

        $client = Cashier::client($gateway);
        if (!$client) {
            return null;
        }

        if (!is_null(Product::provider($gateway))) {
            $productId = $this->package->getGatewayId($gateway);
            $integration = Price::provider($gateway)->create($productId, $this);
        } else {
            $integration = Price::provider($gateway)->create($this);
        }

        if ($integration && isset($integration->id)) {
            $model = $this->gatewayIntegrations()->create([
                'id' => $integration->id,
                'gateway' => $gateway
            ]);

            return $model->id;
        }

        return null;
    }
}