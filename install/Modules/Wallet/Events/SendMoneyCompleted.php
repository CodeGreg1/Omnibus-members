<?php

namespace Modules\Wallet\Events;

use Modules\Wallet\Models\Wallet;
use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class SendMoneyCompleted
{
    use SerializesModels, Dispatchable;

    public $senderWallet;

    public $receiverWallet;

    public $payload;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Wallet $senderWallet, Wallet $receiverWallet, $payload = [])
    {
        $this->senderWallet = $senderWallet;
        $this->receiverWallet = $receiverWallet;
        $this->payload = $payload;

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
            $this->senderWallet,
            'money',
            'sent'
        ));

        event(new LogActivityEvent(
            $this->receiverWallet,
            'money',
            'received'
        ));
    }
}
