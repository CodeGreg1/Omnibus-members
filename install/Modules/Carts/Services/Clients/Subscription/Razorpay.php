<?php

namespace Modules\Carts\Services\Clients\Subscription;

use Modules\Carts\Models\Checkout;
use Stripe\Service\Issuing\IssuingServiceFactory;
use Modules\Carts\Services\Clients\AbstractClientService;

class Razorpay extends AbstractClientService
{
    protected $gateway = 'razorpay';

    public function process(Checkout $checkout)
    {
        $lineItem = $checkout->lineItems()->first();

        if ($checkout->hasDiscount()) {
            $planId = null;
            $period = '';
            $interval = $lineItem->checkoutable->term->interval_count;
            if ($lineItem->checkoutable->term->interval === 'day') {
                if ($lineItem->checkoutable->term->interval_count === 7) {
                    $period = 'weekly';
                    $interval = 1;
                } else {
                    $period = 'daily';
                }
            } else {
                $period = $lineItem->checkoutable->term->interval . 'ly';
            }

            $total = $checkout->getTotal(
                false,
                $lineItem->checkoutable->currency
            );

            $plan = $this->client->prices->create([
                'period' => $period,
                'interval' => $interval,
                'item' => [
                    'name' => $lineItem->checkoutable->package->name,
                    'amount' => amount_to_cents($total),
                    'currency' => $lineItem->checkoutable->currency,
                    'description' => $lineItem->checkoutable->package->name
                ]
            ]);

            if ($plan && isset($plan->id)) {
                $planId = $plan->id;
            }
        } else {
            $planId = $lineItem->checkoutable->getGatewayId($this->gateway);
        }

        $payload = [
            'plan_id' => $planId,
            'total_count' => 1200,
            'quantity' => 1,
            'customer_notify' => 1,
            'notes' => [
                'checkoutId' => $checkout->id,
                'userId' => $checkout->customer_id,
                'priceId' => $lineItem->checkoutable_id
            ]
        ];

        if ($lineItem->checkoutable->trial_days_count) {
            $payload['start_at'] = now()->add(
                'day',
                $lineItem->checkoutable->trial_days_count
            )->timestamp;
        }

        $response = $this->client->subscriptions->create($payload);
        if ($response && isset($response->id)) {
            $checkout->update([
                'metadata' => [
                    'subscriptionId' => $response->id
                ]
            ], (array) $checkout->getMetadata());

            return (object) [
                'url' => $response->short_url
            ];
        }
    }

    public function store($checkout)
    {
    }

    public function getToken(array $attributes)
    {
        return $attributes['checkout'];
    }
}
