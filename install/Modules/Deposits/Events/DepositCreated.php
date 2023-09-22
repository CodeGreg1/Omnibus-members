<?php

namespace Modules\Deposits\Events;

use Modules\Deposits\Models\Deposit;
use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class DepositCreated
{
    use SerializesModels, Dispatchable;

    public $deposit;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Deposit $deposit)
    {
        $this->deposit = $deposit;

        deposit_request_count(1);
        $this->setLogActivity();
    }

    /**
     * Created log activity
     *
     * @return mixed
     */
    protected function setLogActivity()
    {
        event(new LogActivityEvent(
            $this->deposit,
            'deposit',
            'created'
        ));
    }
}
