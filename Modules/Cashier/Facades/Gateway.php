<?php

namespace Modules\Cashier\Facades;

use Illuminate\Support\Facades\Facade;

class Gateway extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cashier.gateway';
    }
}