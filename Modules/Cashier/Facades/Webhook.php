<?php

namespace Modules\Cashier\Facades;

use Illuminate\Support\Facades\Facade;

class Webhook extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cashier.webhook';
    }
}