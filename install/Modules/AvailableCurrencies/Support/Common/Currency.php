<?php

if (!function_exists('currency')) {
    /**
     * Convert given number.
     *
     * @param float  $amount
     * @param string $from
     * @param string $to
     * @param bool   $format
     *
     * @return \Modules\AvailableCurrencies\Services\Currency|string
     */
    function currency($amount = null, $from = null, $to = null, $format = true)
    {
        if (is_null($amount)) {
            return app('currency');
        }

        return app('currency')->convert($amount, $from, $to, $format);
    }
}

if (!function_exists('currency_format')) {
    /**
     * Format given number.
     *
     * @param float  $amount
     * @param string $currency
     * @param bool   $include_symbol
     *
     * @return string
     */
    function currency_format($amount = null, $currency = null, $include_symbol = true)
    {
        return app('currency')->format($amount, $currency, $include_symbol);
    }
}

if (!function_exists('normalize_amount')) {
    /**
     * Format amount in cents to real amount.
     *
     * @param float  $amount
     *
     * @return string
     */
    function normalize_amount($amount = null)
    {
        return $amount * 0.01;
    }
}

if (!function_exists('amount_to_cents')) {
    /**
     * Format amount to cents.
     *
     * @param float  $amount
     *
     * @return string
     */
    function amount_to_cents($amount = null)
    {
        return (int) (round($amount, 2) * 100);
    }
}

if (!function_exists('number_precision')) {
    /**
     * Format amount to desired decimal points without converting.
     *
     * @param float $amount
     * @param int $decimals
     *
     * @return string
     */
    function number_precision($amount = 0, $decimals = 0)
    {
        return app('currency')->numberFormat($amount, $decimals);
    }
}