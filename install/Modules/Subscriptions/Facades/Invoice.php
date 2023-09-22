<?php

namespace Modules\Subscriptions\Facades;

use Illuminate\Support\Facades\Facade;

class Invoice extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'subscription.invoice';
    }
}