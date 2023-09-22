<?php

namespace Modules\Withdrawals\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Modules\Withdrawals\Models\Withdrawal;
use Illuminate\Foundation\Events\Dispatchable;

class WithdrawRequestCreated
{
    use SerializesModels, Dispatchable;

    public $withdraw;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Withdrawal $withdraw)
    {
        $this->withdraw = $withdraw;

        withdrawal_request_count(1);
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
            $this->withdraw,
            'withdraw',
            'created'
        ));
    }
}
