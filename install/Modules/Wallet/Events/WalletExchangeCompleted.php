<?php

namespace Modules\Wallet\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class WalletExchangeCompleted
{
    use SerializesModels, Dispatchable;

    /**
     * @var Illuminate\Database\Eloquent\Model $fromWallet
     */
    public $fromWallet;

    /**
     * @var Illuminate\Database\Eloquent\Model $toWallet
     */
    public $toWallet;

    /**
     * @var array $fromAttributes
     */
    public $fromAttributes;

    /**
     * @var array $toAttributes
     */
    public $toAttributes;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($fromWallet, $toWallet, $fromAttributes, $toAttributes)
    {
        $this->fromWallet = $fromWallet;
        $this->toWallet = $toWallet;
        $this->fromAttributes = $fromAttributes;
        $this->toAttributes = $toAttributes;

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
            $this->toWallet,
            'wallet exchange',
            'completed'
        ));
    }
}
