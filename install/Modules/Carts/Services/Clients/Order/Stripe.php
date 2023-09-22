<?php

namespace Modules\Carts\Services\Clients\Order;

use Illuminate\Support\Str;
use Modules\Carts\Models\Checkout;
use Modules\Carts\Services\Clients\AbstractClientService;

class Stripe extends AbstractClientService
{
    protected $gateway = 'stripe';

    public function process(Checkout $checkout)
    {
        $total = $checkout->getTotal(false, 'USD');

        $payload = [
            'success_url' => route('user.pay.approval', [$checkout->id]) . '?token={CHECKOUT_SESSION_ID}',
            'cancel_url' => $checkout->cancel_url,
            'payment_method_types' => $this->config['payment_methods'] ?? ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Order'
                        ],
                        'unit_amount' => amount_to_cents($total)
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment'
        ];

        return $this->client->checkout->create($payload);
    }

    public function store($token)
    {
        $response = $this->client->checkout->retrieve($token);
        if (isset($response->id)) {
            return [
                'id' => $response->id,
                'object' => $response->object,
                'value' => normalize_amount($response->amount_total),
                'currency' => Str::upper($response->currency)
            ];
        }

        return null;
    }

    public function getToken(array $attributes)
    {
        return $attributes['token'];
    }
}