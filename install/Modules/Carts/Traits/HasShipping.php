<?php

namespace Modules\Carts\Traits;

use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Carts\Models\ShippingRate;

trait HasShipping
{
    /**
     * Get the shipping rate of the checkout
     */
    public function shippingRate()
    {
        return $this->belongsTo(ShippingRate::class);
    }

    /**
     * Get shipping price
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|int|float
     */
    public function getShippingPrice($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        if ($this->shippingRate) {
            return $this->shippingRate->getPrice($formatted, $currency);
        }

        if (!$formatted) {
            return 0;
        }

        return currency_format(0, $currency);
    }
}