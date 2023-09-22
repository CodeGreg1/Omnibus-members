<?php

namespace Modules\Subscriptions\Services\Service\Subscription\Clients;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\Subscription;
use Modules\Subscriptions\Services\Service\Subscription\SubscriptionServiceClient;

class Mollie extends SubscriptionServiceClient
{
    protected $gateway = 'mollie';

    public function retrieve($id)
    {
        return $this->api->subscriptions->retrieve($id);
    }

    public function getCustomerConsentUrl(Subscription $subscription, PackagePrice $price)
    {
        $total = $price->getUnitPrice(false);
        $customerId = $subscription->getMeta('customerId');

        $description = $price->isRecurring()
            ? ($price->term->title . ' payment')
            : 'Lifetime subscription creation';

        $payload = [
            'amount' => [
                'currency' => $price->currency,
                'value' => number_format($total, 2, '.', '')
            ],
            'description' => $description,
            'sequenceType' => 'first',
            'redirectUrl' => route('user.subscriptions.change-package.complete', [
                'subscription' => $subscription->id
            ]),
            'cancelUrl' => route('profile.billing'),
            'sequenceType' => 'oneoff',
            'customerId' => $customerId,
            'webhookUrl' => route('cashier.webhook.handle', $this->gateway)
        ];

        $response = $this->api->payments->create($payload);

        if ($response && isset($response->id)) {
            $subscription->addMeta('paymentId', $response->id);

            return $response->_links->checkout->href;
        }

        return null;
    }

    public function completeChangePrice(
        Subscription $subscription,
        PackagePrice $price,
        $attributes = []
    ) {
        $paymentId = $subscription->getMeta('paymentId');
        $payment = $this->api->payments->retrieve($paymentId);
        if ($payment && isset($payment->status) && $payment->status === 'paid') {
            $subscription->cancel('now');
            $total = $price->getUnitPrice(false);

            if ($price->isRecurring()) {
                if ($price->term->interval === 'year') {
                    $count = $price->term->interval_count * 12;
                    $interval = $count . ' months';
                } else {
                    $interval = $price->term->interval_count . ' ' . Str::plural(
                        $price->term->interval,
                        $price->term->interval_count
                    );
                }

                $stratDate = now()->add(
                    $price->term->interval,
                    $price->term->interval_count
                )->format('Y-m-d');

                $payload = [
                    'amount' => [
                        'currency' => $price->currency,
                        'value' => number_format($total, 2, '.', '')
                    ],
                    'interval' => $interval,
                    'startDate' => $stratDate,
                    'description' => $price->term->title . ' payment (' . now()->format('Y-m-d H:i:s') . ')',
                    'metadata' => [
                        'subscriptionId' => $subscription->id
                    ],
                    'webhookUrl' => route('cashier.webhook.handle', $this->gateway)
                ];

                $response = $this->api->subscriptions->create(
                    $payment->customerId,
                    $payload
                );

                if ($response && isset($response->id)) {
                    $ends_at = Carbon::create($response->nextPaymentDate)
                        ->format('Y-m-d H:i:s');

                    $payload = [
                        'ref_profile_id' => $response->id,
                        'name' => 'main',
                        'gateway' => $this->gateway,
                        'trial_ends_at' => null,
                        'starts_at' => now()->format('Y-m-d H:i:s'),
                        'ends_at' => $ends_at,
                        'meta' => json_encode($response),
                        'payment' => [
                            'transaction_id' => $payment->id,
                            'currency' => $payment->amount->currency,
                            'total' => $payment->amount->value
                        ]
                    ];

                    return $payload;
                }
            } else {
                return [
                    'ref_profile_id' => (string) Str::uuid(),
                    'name' => 'main',
                    'gateway' => $this->gateway,
                    'trial_ends_at' => null,
                    'starts_at' => now()->format('Y-m-d H:i:s'),
                    'ends_at' => null,
                    'recurring' => 0,
                    'meta' => json_encode($payment),
                    'payment' => [
                        'transaction_id' => $payment->id,
                        'currency' => $payment->amount->currency,
                        'total' => $payment->amount->value
                    ]
                ];
            }
        }

        return null;
    }
}
