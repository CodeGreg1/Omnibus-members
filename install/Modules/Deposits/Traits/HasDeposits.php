<?php

namespace Modules\Deposits\Traits;

use Modules\Deposits\Models\Deposit;

trait HasDeposits
{
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}