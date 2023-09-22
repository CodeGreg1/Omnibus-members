<?php

namespace Modules\Deposits\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Deposits\Models\Deposit;
use Modules\Deposits\Events\DepositCreated;
use Modules\Transactions\Models\Transaction;
use Modules\Base\Repositories\BaseRepository;

class DepositsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Deposit::class;
}
