<?php

namespace Modules\Subscriptions\Traits;

use Modules\AvailableCurrencies\Facades\Currency;

trait ManageTotal
{
    /**
     * Get the subscription price amount
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getTotal($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $price = $this->item->price->getUnitPrice(false, $currency);

        if ($this->hasDiscount()) {
            $price = $price - $this->getTotalDiscount(false, $currency);
        }

        if (!$formatted) {
            return $price;
        }

        return currency_format(
            $price,
            $currency
        );
    }

    /**
     * Get order sub total amount
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getSubtotal($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $price = $this->item->price->getUnitPrice(false, $currency);

        if (!$formatted) {
            return $price;
        }

        return currency_format(
            $price,
            $currency
        );
    }

    /**
     * Get order shippingamount
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getShippingAmount($formatted = true, $currency = null)
    {
        $amount = 0;
        if (!$formatted) {
            return $amount;
        }

        $currency = $currency ? $currency : Currency::getUserCurrency();

        return currency_format(
            $amount,
            $currency
        );
    }

    /**
     * Get order total tax amount
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getTotalTax($formatted = true, $currency = null)
    {
        $amount = 0;
        if (!$formatted) {
            return $amount;
        }

        $currency = $currency ? $currency : Currency::getUserCurrency();

        return currency_format(
            $amount,
            $currency
        );
    }
}