<?php

namespace Modules\Transactions\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleCheckoutSubscriptionCompleted
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
        if (isset($event->payload['payment']) && isset($event->payload['wallet'])) {
            $description = 'Paid ' . currency_format($event->payload['payment']['total'], $event->payload['payment']['currency']) . ' for Subscription creation from wallet';

            $event->payload['wallet']->transactions()->create([
                'amount' => $event->payload['payment']['total'],
                'description' => $description,
                'user_id' => $event->checkout->customer_id,
                'currency' => $event->payload['payment']['currency'],
                'initial_balance' => $event->payload['wallet']->balance + $event->payload['payment']['total'],
                'added' => 0
            ]);
        }
    }
}
