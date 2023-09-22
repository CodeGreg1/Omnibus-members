<?php

namespace Modules\Subscriptions\Exceptions;

use Exception;

class UnprocessablePackagesException extends Exception
{
    /**
     * Create a new Unprocessable Packages
     *
     * @return static
     */
    public static function hasSubscriptions($packages)
    {
        return new static(__('Packages: :packages has subscriptions, cannot be removed', [
            'packages' => implode(", ", $packages)
        ]));
    }
}