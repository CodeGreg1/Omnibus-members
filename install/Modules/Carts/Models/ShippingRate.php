<?php

namespace Modules\Carts\Models;

use Modules\Cashier\Facades\Cashier;
use Illuminate\Database\Eloquent\Model;
use Modules\Cashier\Traits\CashierModeScope;
use Modules\AvailableCurrencies\Facades\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingRate extends Model
{
    use HasFactory, CashierModeScope;

    protected $fillable = [
        "title",
        "price",
        "currency",
        "active",
        "metadata",
        "default",
        "live"
    ];

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
        'price_display'
    ];

    /**
     * Get formatted amount with currency.
     *
     * @param $value
     *
     * @return string
     */
    public function getPriceDisplayAttribute($value)
    {
        if (!$this->price) {
            return 'Free';
        }

        return currency(
            $this->price,
            $this->currency,
            Currency::getUserCurrency()
        );
    }

    /**
     * Get actual price of shipping rate by user currency
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float|int
     */
    public function getPrice($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $shippingPrice = currency(
            $this->price,
            $this->currency,
            $currency,
            false
        );

        $shippingPrice = number_format($shippingPrice, 2, '.', '');

        if (!$formatted) {
            return $shippingPrice;
        }

        return currency_format(
            $shippingPrice,
            $currency
        );
    }
}