<?php

namespace Modules\Transactions\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleMoneySent
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
        $event->senderWallet->transactions()->create([
            'amount' => $event->payload['amount'],
            'description' => 'Sent ' . $event->payload['amount_display'] . ' to ' . $event->receiverWallet->user->email,
            'user_id' => $event->senderWallet->user_id,
            'currency' => $event->senderWallet->currency,
            'fixed_charge' => $event->payload['fixed_charge'],
            'percent_charge_rate' => $event->payload['rate'],
            'percent_charge' => $event->payload['rate_charge'],
            'charge' => $event->payload['total_charge'],
            'added' => 0,
            'initial_balance' => $event->payload['sender_balance']
        ]);

        $event->receiverWallet->transactions()->create([
            'amount' => $event->payload['amount'],
            'description' => 'Received ' . $event->payload['amount_display'] . ' from ' . $event->senderWallet->user->email,
            'user_id' => $event->receiverWallet->user_id,
            'currency' => $event->receiverWallet->currency,
            'added' => 1,
            'initial_balance' => $event->payload['receiver_balance']
        ]);
    }
}
