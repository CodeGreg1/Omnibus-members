<?php

namespace Modules\Wallet\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleApprovedWithdraw
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $wallet = $event->withdraw->user
            ->wallets()->where('currency', $event->withdraw->currency)->first();

        if ($wallet) {
            $balance = $wallet->balance - $event->withdraw->amount;
            $wallet->balance = $balance > 0 ? $balance : 0;
            $wallet->save();
        }
    }
}
