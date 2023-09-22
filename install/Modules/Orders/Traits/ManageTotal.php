<?php

namespace Modules\Orders\Traits;

use Modules\AvailableCurrencies\Facades\Currency;

trait ManageTotal
{
    /**
     * Get order total amount
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getTotal($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        return currency(
            $this->total_price,
            $this->currency,
            $currency,
            $formatted
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

        return currency(
            $this->subtotal_price,
            $this->currency,
            $currency,
            $formatted
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
        $currency = $currency ? $currency : Currency::getUserCurrency();

        return currency(
            $this->shipping_amount,
            $this->currency,
            $currency,
            $formatted
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
        $currency = $currency ? $currency : Currency::getUserCurrency();

        return currency(
            $this->total_tax,
            $this->currency,
            $currency,
            $formatted
        );
    }

    /**
     * Get order total discount amount
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getTotalDiscount($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        return currency(
            $this->total_discounts,
            $this->currency,
            $currency,
            $formatted
        );
    }
}