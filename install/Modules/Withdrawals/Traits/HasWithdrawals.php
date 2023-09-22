<?php

namespace Modules\Withdrawals\Traits;

use Modules\Withdrawals\Models\Withdrawal;

trait HasWithdrawals
{
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}