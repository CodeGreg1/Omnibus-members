<?php

namespace Modules\Carts\Services\Clients\SubscriptionOnetime;

use Illuminate\Support\Str;
use Modules\Carts\Models\Checkout;
use Modules\Carts\Services\Clients\AbstractClientService;

class Stripe extends AbstractClientService
{
    protected $gateway = 'stripe';

    public function process(Checkout $checkout)
    {
        $lineItem = $checkout->lineItems->first();
        $total = $checkout->getTotal(false, $lineItem->checkoutable->currency);

        $payload = [
            'success_url' => route('user.pay.approval', [$checkout->id]) . '?token={CHECKOUT_SESSION_ID}',
            'cancel_url' => $checkout->cancel_url,
            'payment_method_types' => $this->config['payment_methods'] ?? ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => Str::lower($lineItem->checkoutable->currency),
                        'product_data' => [
                            'name' => 'Lifetime subscription'
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

        if (isset($response->id) && $response->payment_status === 'paid') {
            return [
                'ref_profile_id' => (string) Str::uuid(),
                'name' => 'main',
                'trial_ends_at' => null,
                'starts_at' => now()->format('Y-m-d H:i:s'),
                'ends_at' => null,
                'meta' => json_encode($response),
                'payment' => [
                    'transaction_id' => $response->payment_intent,
                    'currency' => Str::upper($response->currency),
                    'total' => normalize_amount($response->amount_total)
                ]
            ];
        }

        return null;
    }

    public function getToken(array $attributes)
    {
        return $attributes['token'];
    }
}