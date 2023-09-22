<?php

namespace Modules\Wallet\Exceptions;

use Exception;

class CannotUpdateCurrencyManualGatewayException extends Exception
{
    /**
     * Create a new Invalid credentials.
     *
     * @return static
     */
    public static function notAllowed()
    {
        return new static(__("You cant't change currency for this method."));
    }
}