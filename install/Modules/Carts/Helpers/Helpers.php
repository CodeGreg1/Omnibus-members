<?php

namespace Modules\Carts\Helpers;

use Illuminate\Support\Arr;
use NumberFormatter;

class Helpers
{
    /**
     * normalize price
     *
     * @param $price
     * @return float
     */
    public static function normalizePrice($price)
    {
        return (is_string($price)) ? floatval($price) : $price;
    }

    /**
     * check if array is multi dimensional array
     * This will only check the first element of the array if it is still an array
     * to decide that it is a multi dimensional, if you want to check the array strictly
     * with all on its element, flag the second argument as true
     *
     * @param $array
     * @param bool $recursive
     * @return bool
     */
    public static function isMultiArray($array, $recursive = false)
    {
        if ($recursive) {
            return (count($array) == count($array, COUNT_RECURSIVE)) ? false : true;
        } else {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public static function formatValue($value, $format_numbers, $config)
    {
        if ($format_numbers && $config['format_numbers']) {
            return number_format($value, $config['decimals'], $config['dec_point'], $config['thousands_sep']);
        } else {
            return $value;
        }
    }

    /**
     * Format the given amount into a displayable currency.
     *
     * @param  int  $amount
     * @param  string|null  $currency
     * @param  string|null  $locale
     * @return string
     */
    public static function formatAmount($amount, $currency = null, $formatted = false)
    {
        $money = money($amount, currency(strtoupper($currency)));

        if ($formatted) {
            return $money->format();
        }

        return $money->formatByIntlLocalizedDecimal(null, null, NumberFormatter::PATTERN_DECIMAL);
    }

    /**
     * format amount to cents
     *
     * @return int
     */
    public static function amountCents($amount)
    {
        return (int) (round($amount, 2) * 100);
    }


    /**
     * get only fillable attributes of given model
     *
     * @param Model $model
     * @param array $attributes
     * @return array
     */
    public static function getFillableAttributes($model, $attributes)
    {
        return Arr::only($attributes, $model->getFillable());
    }
}