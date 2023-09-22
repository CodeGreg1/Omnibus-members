<?php

namespace Modules\Cashier\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
    /**
     * Create a new Invalid credentials.
     *
     * @param string $gateway
     * @return static
     */
    public static function invalidCredentials($gateway)
    {
        return new static(__(":gateway: The credentials are not valid.", [
            'gateway' => $gateway
        ]));
    }
}