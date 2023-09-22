<?php

namespace Modules\Withdrawals\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Withdrawals\Models\Withdrawal;
use Modules\Deposits\Events\DepositCreated;
use Modules\Transactions\Models\Transaction;
use Modules\Base\Repositories\BaseRepository;

class WithdrawalsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Withdrawal::class;
}
