<?php

namespace Modules\Dashboard\Services;

use App\Models\User;
use Modules\Wallet\Models\Wallet;
use Modules\Deposits\Models\Deposit;
use Modules\Withdrawals\Models\Withdrawal;

class AdminWalletOverview extends WalletOverview
{
    public function get(User $user)
    {
        if (!$user->isAdmin()) {
            return false;
        }

        return [
            'deposit' => $this->getDepositOverview(Deposit::query(), Wallet::query()),
            'withdrawal' => $this->getWithdrawalOverview(Withdrawal::query()),
            'transactions' => $this->getLatestTransactions()
        ];
    }
}
