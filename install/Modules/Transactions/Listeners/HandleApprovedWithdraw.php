<?php

namespace Modules\Transactions\Listeners;

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
        $initialBalance = 0;
        $wallet = $event->withdraw->user->getWalletByCurrency($event->withdraw->currency);
        if ($wallet) {
            $initialBalance = $wallet->balance;
        }


        $event->withdraw->transactions()->create([
            'description' => 'Withdraw ' . currency_format($event->withdraw->amount, $event->withdraw->currency) . ' via ' . $event->withdraw->method->name,
            'user_id' => $event->withdraw->user_id,
            'currency' => $event->withdraw->currency,
            'amount' => $event->withdraw->amount,
            'fixed_charge' => $event->withdraw->fixed_charge,
            'percent_charge_rate' => $event->withdraw->percent_charge_rate,
            'percent_charge' => $event->withdraw->percent_charge,
            'charge' => $event->withdraw->charge,
            'added' => 0,
            'initial_balance' => $initialBalance
        ]);
    }
}
