<?php

namespace Modules\Wallet\Traits;

use Illuminate\Database\Eloquent\Builder;

trait WalletCurrencyScope
{
    protected static function bootWalletCurrencyScope()
    {
        static::addGlobalScope('currency', function (Builder $builder) {
            $builder->when(
                setting('allow_wallet_multi_currency') !== 'enable',
                function (Builder $builder) {
                    return $builder->where('currency', setting('currency'));
                }
            );
        });
    }
}