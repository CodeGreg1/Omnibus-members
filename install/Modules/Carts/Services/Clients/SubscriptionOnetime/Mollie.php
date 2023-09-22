<?php

namespace Modules\Carts\Services\Clients\SubscriptionOnetime;

use Illuminate\Support\Str;
use Modules\Carts\Models\Checkout;
use Modules\Carts\Services\Clients\AbstractClientService;

class Mollie extends AbstractClientService
{
    protected $gateway = 'mollie';

    public function process(Checkout $checkout)
    {
        $lineItem = $checkout->lineItems->first();
        $total = $checkout->getTotal(false, $lineItem->checkoutable->currency);

        $customer = $this->client->customers->create([
            'name' => $checkout->customer->full_name,
            'email' => $checkout->customer->email,
        ]);

        if ($customer && isset($customer->id)) {
            $payload = [
                'description' => 'Lifetime subscription creation',
                'redirectUrl' => route('user.pay.approval', [$checkout->id]),
                'cancelUrl' => $checkout->cancel_url,
                'customerId' => $customer->id,
                'sequenceType' => 'first',
                'amount' => [
                    'currency' => $lineItem->checkoutable->currency,
                    'value' => number_format($total, 2, '.', '')
                ],
                'metadata' => [
                    'checkoutId' => $checkout->id
                ],
                'webhookUrl' => route('cashier.webhook.handle', $this->gateway)
            ];

            $response = $this->client->payments->create($payload);

            if ($response && isset($response->id) && $response->id) {
                $checkout->update([
                    'metadata' => [
                        'paymentId' => $response->id
                    ]
                ], (array) $checkout->getMetadata());

                return (object) [
                    'url' => $response->_links->checkout->href
                ];
            }
        }

        return null;
    }

    public function store($checkout)
    {
        $paymentId = $checkout->getMetadata('paymentId');
        $payment = $this->client->payments->retrieve($paymentId);
        if ($payment && isset($payment->status) && $payment->status === 'paid') {
            return [
                'ref_profile_id' => (string) Str::uuid(),
                'name' => 'main',
                'trial_ends_at' => null,
                'starts_at' => now()->format('Y-m-d H:i:s'),
                'ends_at' => null,
                'meta' => json_encode($payment),
                'payment' => [
                    'transaction_id' => $payment->id,
                    'currency' => $payment->amount->currency,
                    'total' => $payment->amount->value
                ]
            ];
        }

        return null;
    }

    public function getToken(array $attributes)
    {
        return $attributes['checkout'];
    }
}
