<?php

namespace Modules\Products\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Products\Models\Product;

class HandleOrderCreated
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
        $event->order->items()->get()->each(function ($item) {
            if ($item->orderable instanceof Product) {
                // Decrement stock
                if ($item->orderable->cartStock() < $item->quantity) {
                    $item->orderable->stock = 0;
                    $item->orderable->save();
                } else {
                    $item->orderable->decrement('stock', $item->quantity);
                }
            }
        });
    }
}