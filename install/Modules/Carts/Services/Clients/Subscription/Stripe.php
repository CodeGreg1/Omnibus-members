<?php

namespace Modules\Carts\Services\Clients\Subscription;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Modules\Carts\Models\Checkout;
use Modules\Cashier\Facades\Cashier;
use Modules\Carts\Services\Clients\AbstractClientService;

class Stripe extends AbstractClientService
{
    protected $gateway = 'stripe';

    public function process(Checkout $checkout)
    {
        $lineItem = $checkout->lineItems()->first();

        $payload = [
            'success_url' => route('user.pay.approval', [$checkout->id]) . '?subscription_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $checkout->cancel_url,
            'payment_method_types' => ['card'],
            'mode' => 'subscription'
        ];

        if ($lineItem->checkoutable->trial_days_count) {
            $payload['subscription_data'] = [
                'trial_period_days' => $lineItem->checkoutable->trial_days_count
            ];
        }

        if ($checkout->hasDiscount()) {
            $productId = $lineItem->checkoutable->package->getGatewayId($this->gateway);
            if (!$productId) {
                return null;
            }

            $total = $checkout->getTotal(
                false,
                $lineItem->checkoutable->currency
            );
	    
            $payload['line_items'] = [
                [
                    'price_data' => [
                        'unit_amount' => amount_to_cents($total),
                        'currency' => Str::lower($lineItem->checkoutable->currency),
                        'product' => $productId,
                        'recurring' => [
                            'interval' => $lineItem->checkoutable->term->interval,
                            'interval_count' => $lineItem->checkoutable->term->interval_count
                        ]
                    ],
                    'quantity' => 1
                ]

            ];
        } else {
            $planId = $lineItem->checkoutable->getGatewayId($this->gateway);
            $payload['line_items'] = [
                [
                    'price' => $planId,
                    'quantity' => $lineItem->quantity
                ]
            ];
        }

        return $this->client->checkout->create($payload);
    }

    public function store($token)
    {
        $session = $this->client->checkout->retrieve($token);
        if ($session) {
            $subscription = $this->client->subscriptions->retrieve($session->subscription);
            if ($subscription) {
                $trial_ends_at = null;
                if ($subscription->trial_end) {
                    $trial_ends_at = Carbon::createFromTimestamp($subscription->trial_end)
                        ->format('Y-m-d H:i:s');
                }

                $ends_at = null;
                if ($subscription->current_period_end) {
                    $ends_at = Carbon::createFromTimestamp($subscription->current_period_end)
                        ->format('Y-m-d H:i:s');
                }

                $payload = [
                    'ref_profile_id' => $subscription->id,
                    'name' => 'main',
                    'trial_ends_at' => $trial_ends_at,
                    'starts_at' => now()->format('Y-m-d H:i:s'),
                    'ends_at' => $ends_at,
                    'meta' => json_encode($subscription)
                ];

                if (isset($subscription->latest_invoice)) {
                    $invoice = $this->client->invoices->retrieve($subscription->latest_invoice);
                    if ($invoice && $invoice->amount_paid) {
                        $payload['payment'] = [
                            'transaction_id' => $invoice->id,
                            'currency' => Str::upper($invoice->currency),
                            'total' => normalize_amount($invoice->amount_paid)
                        ];
                    }
                }

                return $payload;
            }
        }

        return null;
    }

    public function getToken(array $attributes)
    {
        return $attributes['subscription_id'];
    }
}
