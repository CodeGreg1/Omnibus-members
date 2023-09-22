<?php

if (!function_exists('real_number')) {
    /**
     * Convert numbers readable numbers
     *
     * Example:
     * 1 = 1
     * 1.00 = 1
     *
     * @param mixed $number
     *
     * @return int|float
     */
    function real_number($number)
    {
        $whole = floor($number);      // 1
        $fraction = $number - $whole; // .25
        return $fraction > 0 ? $number : $whole;
    }
}

if (!function_exists('convert_to_cents')) {
    /**
     * Convert number to cents
     * 
     * Example 1: 1.549 turn to 155
     * Example 2: 1.591 turn to 159
     * Example 4: 1591 turn to 159100
     *
     * @param int|float $amount
     *
     * @return int
     */
    function convert_to_cents($amount)
    {
        return (round($amount, 2)) * 100;
    }
}