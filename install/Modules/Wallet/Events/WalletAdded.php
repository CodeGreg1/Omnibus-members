<?php

namespace Modules\Wallet\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class WalletAdded
{
    use SerializesModels, Dispatchable;

    public $user;

    public $wallet;

    public $amount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $wallet, $amount)
    {
        $this->user = $user;
        $this->wallet = $wallet;
        $this->amount = $amount;

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
            $this->wallet,
            'balance',
            'added'
        ));
    }
}
