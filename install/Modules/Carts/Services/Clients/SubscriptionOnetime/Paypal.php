<?php

namespace Modules\Carts\Services\Clients\SubscriptionOnetime;

use Illuminate\Support\Str;
use Modules\Carts\Models\Checkout;
use Modules\Carts\Services\Clients\AbstractClientService;

class Paypal extends AbstractClientService
{
    protected $gateway = 'paypal';

    public function process(Checkout $checkout)
    {
        $lineItem = $checkout->lineItems->first();
        $total = $checkout->getTotal(false, $lineItem->checkoutable->currency);

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $lineItem->checkoutable->currency,
                        'value' => number_format($total, '2', '.', '')
                    ]
                ]
            ],
            'application_context' => [
                'user_action' => 'CONTINUE',
                'return_url' => route('user.pay.approval', [$checkout->id]),
                'cancel_url' => $checkout->cancel_url
            ],
        ];

        return $this->client->checkout->create($payload);
    }

    public function store($token)
    {
        $response = $this->client->checkout->approve($token);

        if ($response) {
            $payment = $response->purchase_units[0]->payments->captures[0];
            return [
                'ref_profile_id' => (string) Str::uuid(),
                'name' => 'main',
                'trial_ends_at' => null,
                'starts_at' => now()->format('Y-m-d H:i:s'),
                'ends_at' => null,
                'meta' => json_encode($response),
                'payment' => [
                    'transaction_id' => $payment->id,
                    'currency' => $payment->amount->currency_code,
                    'total' => $payment->amount->value
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