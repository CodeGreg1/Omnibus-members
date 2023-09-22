<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\AvailableCurrencies\Facades\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "subscription_id",
        "package_price_id",
        "quantity",
        "currency",
        "total"
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['price'];

    /**
     * Get the subscription that owns the item.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the package price that owns the item
     */
    public function price()
    {
        return $this->belongsTo(PackagePrice::class, 'package_price_id');
    }

    /**
     * Get the unit price of the item
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|int|float
     */
    public function getUnitPrice($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $price = number_format(currency(
            $this->total,
            $this->currency,
            $currency,
            false
        ), 2, '.', '');

        if (!$formatted) {
            return (float) $price;
        }

        return currency_format($price, $currency);
    }
}
