<?php

namespace Modules\Pages\Facades;

use Illuminate\Support\Facades\Facade;

class PageBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PageBuilder';
    }
}