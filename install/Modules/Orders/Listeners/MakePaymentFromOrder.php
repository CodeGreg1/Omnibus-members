<?php

namespace Modules\Orders\Listeners;

use Modules\Payments\Services\Payment;

class MakePaymentFromOrder
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
        Payment::make($event->order, [
            'currency' => $event->order->currency,
            'gateway' => $event->order->gateway,
            'total' => $event->order->total_price
        ]);
    }
}