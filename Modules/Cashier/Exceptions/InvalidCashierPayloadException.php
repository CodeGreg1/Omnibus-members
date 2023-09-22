<?php

namespace Modules\Cashier\Exceptions;

use Exception;

class InvalidCashierPayloadException extends Exception
{
    /**
     * Create a new not found payload.
     *
     * @return static
     */
    public static function notFoundPayload()
    {
        return new static(__("No data from payload."));
    }

    /**
     * Create a new view not found.
     *
     * @return static
     */
    public static function viewNotFound($view)
    {
        return new static(__('View: :view not found from resource.', [
            'view' => $view
        ]));
    }
}