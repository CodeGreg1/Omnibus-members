<?php

namespace Modules\Cashier\Exceptions;

use Exception;

class InvalidClient extends Exception
{
    /**
     * Create a new InvalidClient instance.
     *
     * @return static
     */
    public static function undefinedClient()
    {
        return new static(__("The client is not defined."));
    }
}