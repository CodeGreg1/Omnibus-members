<?php

namespace Modules\Wallet\Traits;

use Modules\Wallet\Models\Wallet;

trait WalletBalance
{
    public function walletBalance()
    {
        return floatval(Wallet::where('currency', $this->code)->sum('balance'));
    }
}