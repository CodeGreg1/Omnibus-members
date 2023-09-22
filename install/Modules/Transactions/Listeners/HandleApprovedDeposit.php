<?php

namespace Modules\Transactions\Listeners;

use Modules\Cashier\Facades\Cashier;
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
        $methodName = '';
        if ($event->deposit->method) {
            $methodName = $event->deposit->method->name;
        } else {
            $client = Cashier::getClient($event->deposit->gateway);
            if ($client) {
                $methodName = $client->name;
            }
        }

        $initialBalance = 0;
        $wallet = $event->deposit->user->getWalletByCurrency($event->deposit->currency);
        if ($wallet) {
            $initialBalance = $wallet->balance;
        }

        $event->deposit->transactions()->create([
            'description' => 'Deposited ' . currency_format($event->deposit->amount, $event->deposit->currency) . ' via ' . $methodName,
            'user_id' => $event->deposit->user_id,
            'currency' => $event->deposit->currency,
            'fixed_charge' => $event->deposit->fixed_charge,
            'percent_charge_rate' => $event->deposit->percent_charge_rate,
            'percent_charge' => $event->deposit->percent_charge,
            'amount' => $event->deposit->amount,
            'charge' => $event->deposit->charge,
            'added' => 1,
            'initial_balance' => $initialBalance
        ]);
    }
}
