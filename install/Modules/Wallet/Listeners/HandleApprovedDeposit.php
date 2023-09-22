<?php

namespace Modules\Wallet\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleApprovedDeposit
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
        $wallet = $event->deposit->user
            ->wallets()->where('currency', $event->deposit->currency)->first();

        if ($wallet) {
            $wallet->balance = $wallet->balance + $event->deposit->amount;
            $wallet->save();
        } else {
            $event->deposit->user->wallets()->create([
                'currency' => $event->deposit->currency,
                'balance' => $event->deposit->amount
            ]);
        }
    }
}
