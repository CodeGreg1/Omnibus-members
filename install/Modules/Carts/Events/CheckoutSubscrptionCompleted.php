<?php

namespace Modules\Carts\Events;

use Modules\Carts\Models\Checkout;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CheckoutSubscrptionCompleted
{
    use SerializesModels, Dispatchable;

    public $checkout;

    public $payload;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Checkout $checkout, $payload)
    {
        $this->checkout = $checkout;
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}