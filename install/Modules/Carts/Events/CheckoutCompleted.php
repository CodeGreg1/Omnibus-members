<?php

namespace Modules\Carts\Events;

use Modules\Carts\Models\Checkout;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CheckoutCompleted
{
    use SerializesModels, Dispatchable;

    public $checkout;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Checkout $checkout)
    {
        $this->checkout = $checkout;
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