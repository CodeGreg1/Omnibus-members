<?php

namespace Modules\Subscriptions\Facades;

use Illuminate\Support\Facades\Facade;

class Product extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'subscription.product';
    }
}