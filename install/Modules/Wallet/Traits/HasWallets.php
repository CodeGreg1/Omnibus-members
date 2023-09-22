<?php

namespace Modules\Wallet\Traits;

use Modules\Wallet\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

trait HasWallets
{
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function getWalletByCurrency($currency)
    {
        $currency = str($currency)->upper()->value();
        return $this->wallets->first(function ($wallet) use ($currency) {
            return $wallet->currency === $currency;
        });
    }

    public function getBalanceByCurrency($currency)
    {
        $currency = str($currency)->upper()->value();
        $wallet = $this->getWalletByCurrency($currency);

        if ($wallet) {
            return floatval($wallet->balance);
        }

        return 0;
    }

    public function hasFunds()
    {
        return !!$this->wallets->first(function ($wallet) {
            return $wallet->balance;
        });
    }

    public function addWallet($currency, $amount)
    {
        $currency = str($currency)->upper()->value();
        $wallet = $this->getWalletByCurrency($currency);

        if ($wallet) {
            return $this->loadWallet($wallet, $amount);
        } else {
            return $this->wallets()->create([
                'currency' => $currency,
                'balance' => $amount
            ]);
        }
    }

    public function loadWallet(Wallet $wallet, $amount)
    {
        $wallet->balance = $wallet->balance + $amount;
        $wallet->save();
        return $wallet->fresh();
    }

    public function unLoadWallet(Wallet $wallet, $amount)
    {
        $balance = $wallet->balance - $amount;
        $wallet->balance = $balance > 0 ? $balance : 0;
        $wallet->save();
        return $wallet->fresh();
    }

    public function getWallets()
    {
        $wallets = $this->wallets;
        $result = collect(array_values(currency()->getActiveCurrencies()))
            ->map(function ($item) use ($wallets) {
                $wallet = $wallets->first(function ($w) use ($item) {
                    return $w->currency === $item['code'];
                });

                return (object) [
                    "currency" => $item['code'],
                    "balance" => $wallet ? $wallet->balance : 0
                ];
            });
        if (setting('allow_wallet_multi_currency') !== 'enable') {
            $result = $result->where('currency', setting('currency'));
        }

        return $result;
    }

    /**
     * Scope a query to only include users with balance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasBalance($query)
    {
        return $query->whereHas('wallets', function ($q) {
            $q->where('balance', '>', 0);
        });
    }

    /**
     * Scope a query to only include users with no balance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNoBalance($query)
    {
        return $query->whereHas('wallets', function ($query) {
            return $query->select('balance')
                ->groupBy('balance')
                ->having('balance', '=', 0);
        })->orDoesntHave('wallets');
    }
}