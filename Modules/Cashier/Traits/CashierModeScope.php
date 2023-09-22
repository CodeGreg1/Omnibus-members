<?php

namespace Modules\Cashier\Traits;

use Modules\Cashier\Scopes\LiveScope;

trait CashierModeScope
{
    protected static function bootCashierModeScope()
    {
        static::addGlobalScope(new LiveScope);

        static::creating(function ($model) {
            $model->live = setting('cashier_mode', 'sandbox') === 'sandbox' ? 0 : 1;
        });
    }
}