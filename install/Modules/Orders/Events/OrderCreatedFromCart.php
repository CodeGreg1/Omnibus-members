<?php

namespace Modules\Orders\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Carts\Services\CartCollection;

class OrderCreatedFromCart
{
    use SerializesModels, Dispatchable;

    /**
     * the cart collection of items
     *
     * @var CartCollection
     */
    public $items;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CartCollection $items)
    {
        $this->items = $items;
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