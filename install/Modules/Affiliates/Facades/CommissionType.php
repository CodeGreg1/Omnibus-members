<?php

namespace Modules\Affiliates\Facades;

use Illuminate\Support\Facades\Facade;

class CommissionType extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'affiliate_commission_type';
    }
}