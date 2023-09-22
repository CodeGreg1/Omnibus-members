<?php

namespace Modules\Subscriptions\Exceptions;

use Exception;

class PackagePriceException extends Exception
{
    /**
     * Create a new Unprocessable Package price
     *
     * @return static
     */
    public static function cannotBeDelete($message)
    {
        return new static($message);
    }
}