<?php

namespace Modules\Subscriptions\Facades;

use Illuminate\Support\Facades\Facade;

class Price extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'subscription.price';
    }
}