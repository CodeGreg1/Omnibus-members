<?php

namespace Modules\Subscriptions\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
    /**
     * Create a new Invalid credentials.
     *
     * @return static
     */
    public static function invalidCredentials()
    {
        return new static("The credentials are not valid.");
    }
}