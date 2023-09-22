<?php

namespace Modules\Carts\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Carts\Facades\Cart;
use Modules\Carts\Models\CartItem;

class RemoveCartItems
{
    /**
     * Cart Item model instance
     *
     * @var CartItem
     */
    protected $cartItems;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cartItems = new CartItem();
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $items = $event->items->pluck('cart_item_id')->toArray();
        Cart::remove($items);
    }
}