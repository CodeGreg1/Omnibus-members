<?php

namespace Modules\Transactions\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleWalletDeducted
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
        $description = 'Withdraw ' . currency_format($event->amount, $event->wallet->currency) . ' by admin: ' . $event->user->full_name ?? $event->user->email;

        $event->wallet->transactions()->create([
            'amount' => $event->amount,
            'description' => $description,
            'user_id' => $event->wallet->user->id,
            'currency' => $event->wallet->currency,
            'initial_balance' => $event->wallet->balance + $event->amount,
            'added' => 0
        ]);
    }
}
