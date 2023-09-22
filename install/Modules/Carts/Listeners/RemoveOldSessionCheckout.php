<?php

namespace Modules\Carts\Listeners;

use Modules\Carts\Models\Checkout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveOldSessionCheckout
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
        Checkout::where('expires_at', '<=', now())
            ->where('customer_id', $event->checkout->customer_id)
            ->where('id', '!=', $event->checkout->id)
            ->delete();
    }
}
