<?php

namespace Modules\Carts\Exceptions;

use Exception;

class ShippingRateException extends Exception
{
    /**
     * Create a new Invalid credentials.
     *
     * @return static
     */
    public static function noActiveShippingRates()
    {
        return new static(__('Cart checkout not set up'));
    }
}