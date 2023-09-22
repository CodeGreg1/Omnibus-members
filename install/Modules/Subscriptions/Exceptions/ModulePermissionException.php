<?php

namespace Modules\Subscriptions\Exceptions;

use Exception;

class ModulePermissionException extends Exception
{
    /**
     * Create a new Invalid credentials.
     *
     * @return static
     */
    public static function unauthorize($moduleName)
    {
        return new static(__("You dont have authorization to access") . ' ' . $moduleName);
    }
}