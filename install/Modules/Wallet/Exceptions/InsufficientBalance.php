<?php

namespace Modules\Wallet\Exceptions;

use Exception;

class InsufficientBalance extends Exception
{
    /**
     * Create a new Invalid credentials.
     *
     * @return static
     */
    public static function insufficient()
    {
        return new static(__("Insufficient balance from wallet."));
    }
}