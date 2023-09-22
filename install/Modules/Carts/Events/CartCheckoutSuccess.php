<?php

namespace Modules\Carts\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Carts\Services\CartCollection;
use Illuminate\Foundation\Events\Dispatchable;

class CartCheckoutSuccess
{
    use SerializesModels, Dispatchable;

    /**
     * the cart items checkout.
     *
     * @var CartCollection
     */
    public $items;

    /**
     * the attributes to create
     *
     * @var array
     */
    public $attributes;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CartCollection $items, array $attributes)
    {
        $this->items = $items;
        $this->attributes = $attributes;
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