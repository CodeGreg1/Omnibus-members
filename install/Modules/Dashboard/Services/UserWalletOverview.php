<?php

namespace Modules\Dashboard\Services;

use App\Models\User;

class UserWalletOverview extends WalletOverview
{
    public function get(User $user)
    {
        return [
            'deposit' => $this->getDepositOverview($user->deposits(), $user->wallets()),
            'withdrawal' => $this->getWithdrawalOverview($user->withdrawals()),
            'transactions' => $this->getLatestTransactions($user)
        ];
    }
}