<?php

namespace Modules\Wallet\Exceptions;

use Exception;

class ModuleNotAllowedException extends Exception
{
    /**
     * Create a new Invalid credentials.
     *
     * @return static
     */
    public static function notAllowed()
    {
        return new static(__("You dont have access to this module."));
    }
}