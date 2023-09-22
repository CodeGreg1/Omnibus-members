<?php

namespace Modules\Carts\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleCheckoutCompleted
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
        if ($event->checkout->hasDiscount()) {
            $event->checkout->promoCode->used();
        }

        $event->checkout->lineItems->map(function ($item) {
            if (method_exists($item->checkoutable, 'purchasables')) {
                $cartItems = $item->checkoutable->purchasables()->get();
                $cartItems->each(function ($cartItem) use ($item) {
                    if ($item->quantity === $cartItem->quantity) {
                        $cartItem->delete();
                    } else {
                        $quantity = $cartItem->quantity - $item->quantity;
                        if ($quantity > 0) {
                            $cartItem->quantity = $quantity;
                            $cartItem->save();
                        } else {
                            $cartItem->delete();
                        }
                    }
                });
            }
        });

        // $event->checkout->delete();
    }
}