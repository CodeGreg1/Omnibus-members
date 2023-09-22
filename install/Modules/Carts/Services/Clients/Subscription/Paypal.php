<?php

namespace Modules\Carts\Services\Clients\Subscription;

use Carbon\Carbon;
use Modules\Carts\Models\Checkout;
use Modules\Carts\Services\Clients\AbstractClientService;

class Paypal extends AbstractClientService
{
    protected $gateway = 'paypal';

    public function process(Checkout $checkout)
    {
        $lineItem = $checkout->lineItems->first();

        $planId = $lineItem->checkoutable->getGatewayId($this->gateway);

        if ($planId) {
            $payload = [
                'plan_id' => $planId,
                'quantity' => $lineItem->quantity,
                'application_context' => [
                    'user_action' => 'CONTINUE',
                    'return_url' => route('user.pay.approval', [$checkout->id]),
                    'cancel_url' => $checkout->cancel_url,
                ]
            ];

            if ($checkout->hasDiscount()) {
                $sequence = $lineItem->checkoutable->trial_days_count ? 2 : 1;
                $total = $checkout->getTotal(
                    false,
                    $lineItem->checkoutable->currency
                );
                $payload['plan'] = [
                    'billing_cycles' => [
                        [
                            'sequence' => $sequence,
                            'pricing_scheme' => [
                                'fixed_price' => [
                                    'currency_code' => $lineItem->checkoutable->currency,
                                    'value' => number_format($total, 2, '.', '')
                                ]
                            ]
                        ]
                    ]
                ];
            }

            $response = $this->client->subscriptions->create($payload);

            if ($response && isset($response->status) && $response->status === 'APPROVAL_PENDING') {
                $url = collect($response->links)->first(function ($link) {
                    return $link->rel === 'approve';
                });

                if ($url) {
                    $response->url = $url->href;
                }
            }

            return $response;
        }

        return null;
    }

    public function store($token)
    {
        $activated = $this->client->subscriptions->activate($token);

        if ($activated) {
            $subscription = $this->client->subscriptions->retrieve($token);
            $starts_at = now()->format('Y-m-d H:i:s');
            $trial_ends_at = null;

            if ($subscription->start_time) {
                $starts_at = Carbon::create($subscription->start_time)->format('Y-m-d H:i:s');
            }

            if (
                $subscription->billing_info->cycle_executions
                && count($subscription->billing_info->cycle_executions)
            ) {
                $firstBilling = $subscription->billing_info->cycle_executions[0];
                if ($firstBilling->tenure_type === 'TRIAL') {
                    $trial_ends_at = 1;
                }
            }

            $payload = [
                'ref_profile_id' => $subscription->id,
                'name' => 'main',
                'trial_ends_at' => $trial_ends_at,
                'starts_at' => $starts_at,
                'ends_at' => null,
                'meta' => json_encode($subscription)
            ];

            if (isset($subscription->billing_info->last_payment)) {
                $payload['payment'] = [
                    'transaction_id' => uniqid('TEMPINV-ID-'),
                    'currency' => $subscription->billing_info->last_payment->amount->currency_code,
                    'total' => $subscription->billing_info->last_payment->amount->value
                ];
            }

            return $payload;
        }

        return null;
    }

    public function getToken(array $attributes)
    {
        return $attributes['subscription_id'];
    }
}
