<?php

namespace Modules\Orders\Listeners;

use Illuminate\Support\Facades\DB;
use Modules\Cashier\Facades\Cashier;
use Modules\Orders\Events\OrderCreated;
use Modules\Orders\Models\Order;
use Modules\Payments\Events\PayablePaymentCompleted;

class CreateOrderableFromCheckout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->orders = new Order;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->checkout->lineItems->first()->isOrderable()) {
            if (!$this->orders->where('cart_token', $event->checkout->id)->exists()) {
                $order = DB::transaction(function () use ($event) {
                    $currency = $event->payload ? $event->payload['currency'] : Cashier::currency();
                    $total_price = $event->payload ?
                        $event->payload['value'] :
                        $event->checkout->getTotal(false, Cashier::currency());

                    $order = $this->orders->create([
                        'customer_id' => $event->checkout->customer_id,
                        'cart_token' => $event->checkout->id,
                        'currency' => $currency,
                        'gateway' => $event->checkout->gateway,
                        'total_discounts' => $event->checkout->getDiscountPrice(false, $currency),
                        'shipping_amount' => $event->checkout->getShippingPrice(false, $currency),
                        'total_tax' => $event->checkout->getTotalTax(false, $currency),
                        'subtotal_price' => $event->checkout->getSubtotal(false, $currency),
                        'total_price' => $total_price,
                        'paid' => $event->payload ? 1 : 0,
                        'billing_address_id' => $event->checkout->billing_address_id,
                        'shipping_address_id' => $event->checkout->shipping_address_id,
                        'status' => 'pending',
                        'note' => $event->checkout->getMetadata('note'),
                        'phone' => $event->checkout->getMetadata('phone')
                    ]);

                    $event->checkout->lineItems->each(function ($item, $key) use ($order, $currency) {
                        $item->checkoutable->orderables()->create([
                            'order_id' => $order->id,
                            'price' => $item->getPrice(false, $currency),
                            'quantity' => $item->quantity,
                            'total_tax' => $item->getTotalTax(false, $currency),
                            'total_discounts' => 0,
                            'title' => $item->checkoutable->cartItemName()
                        ]);
                    });
                    return $order;
                }, 3);

                if ($order) {
                    OrderCreated::dispatch($order);

                    if ($order->paid) {
                        PayablePaymentCompleted::dispatch($order);
                    }

                    $event->checkout->checkouted_type = get_class($order);
                    $event->checkout->checkouted_id = $order->id;
                    $event->checkout->save();
                }
            }
        }
    }
}