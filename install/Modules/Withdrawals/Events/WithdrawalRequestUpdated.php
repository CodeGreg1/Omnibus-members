<?php

namespace Modules\Withdrawals\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Modules\Withdrawals\Models\Withdrawal;

class WithdrawalRequestUpdated
{
    use SerializesModels;

    public $withdraw;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Withdrawal $withdraw)
    {
        $this->withdraw = $withdraw;

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
            'updated'
        ));
    }
}
