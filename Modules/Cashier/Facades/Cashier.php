<?php

namespace Modules\Cashier\Facades;

use Illuminate\Support\Facades\Facade;

class Cashier extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cashier';
    }
}