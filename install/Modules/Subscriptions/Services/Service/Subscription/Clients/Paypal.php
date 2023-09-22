<?php

namespace Modules\Subscriptions\Services\Service\Subscription\Clients;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Modules\Subscriptions\Facades\Invoice;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\Subscription;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Subscriptions\Events\SubscriptionCreated;
use Modules\Subscriptions\Services\Service\Subscription\SubscriptionServiceClient;

class Paypal extends SubscriptionServiceClient
{
    protected $gateway = 'paypal';

    public function retrieve($id)
    {
        return $this->api->subscriptions->retrieve($id);
    }

    public function checkout(User $user, PackagePrice $price, $discount = null)
    {
        $gatewayPrice = $price->gatewayPrices()->where('gateway', $this->gateway)->first();

        $payload = [
            'plan_id' => $gatewayPrice->price_id,
            'quantity' => '1',
            'custom_id' => $user->id,
            'application_context' => [
                'user_action' => 'CONTINUE',
                'return_url' => route('user.subscriptions.change-package.complete', [
                    'subscription' => $this->gateway
                ]),
                'cancel_url' => route('user.pay'),
            ]
        ];

        if ($discount) {
            $payload['plan'] = [
                'billing_cycles' => [
                    [
                        'sequence' => $price->trial_days_count ? 2 : 1,
                        'total_cycles' => 0,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'currency_code' => $price->currency,
                                'value' => $discount['amount']
                            ]
                        ]
                    ]
                ]
            ];
        }

        $response = $this->api->subscriptions->create($payload);
        if ($response && isset($response->status) && $response->status === 'APPROVAL_PENDING') {
            $url = collect($response->links)->first(function ($link) {
                return $link->rel === 'approve';
            });

            if ($url) {
                $response->href = $url->href;
            }
        }

        return $response;
    }

    public function complete(User $user, $id, $payload)
    {
        $activated = $this->api->subscriptions->activate($id);

        if ($activated) {
            $subscription = $this->api->subscriptions->retrieve($id);

            if ($subscription && !$user->subscriptionExist($subscription->id, $this->gateway)) {
                $ends_at = null;
                $trial_ends_at = null;
                $starts_at = now()->format('Y-m-d H:i:s');
                if ($subscription->start_time) {
                    $starts_at = Carbon::create($subscription->start_time)->format('Y-m-d H:i:s');
                }

                if ($subscription->billing_info->next_billing_time) {
                    $ends_at = Carbon::create($subscription->billing_info->next_billing_time)
                        ->addDay()
                        ->format('Y-m-d H:i:s');
                }

                if (
                    $subscription->billing_info->cycle_executions
                    && count($subscription->billing_info->cycle_executions)
                ) {
                    $firstBilling = $subscription->billing_info->cycle_executions[0];
                    if ($firstBilling->tenure_type === 'TRIAL') {
                        $trial_ends_at = $ends_at;
                    }
                }

                $userSubscription = $user->subscriptions()->create([
                    'ref_profile_id' => $subscription->id,
                    'package_price_id' => $payload['price']->id,
                    'gateway' => $this->gateway,
                    'name' => 'main',
                    'trial_ends_at' => $trial_ends_at,
                    'starts_at' => $starts_at,
                    'ends_at' => $ends_at,
                    'meta' => json_encode($subscription)
                ]);

                if (isset($payload['discount'])) {
                    $userSubscription->promoCode()->create([
                        'subscription_id' => $userSubscription->id,
                        'promo_code_id' => $payload['discount']['promoCode']->id,
                        'value' => $payload['discount']['promoCode']->coupon->amount,
                        'type' => $payload['discount']['promoCode']->coupon->amount_type,
                        'amount' => $payload['discount']['amount']
                    ]);
                }

                SubscriptionCreated::dispatch($userSubscription);

                return $userSubscription;
            }
        }

        return null;
    }

    public function getCustomerConsentUrl(Subscription $subscription, PackagePrice $price)
    {
        if ($price->isRecurring()) {
            $planId = $price->getGatewayId($this->gateway);

            if ($planId) {
                $response = $this->api->subscriptions->create([
                    'plan_id' => $planId,
                    'quantity' => 1,
                    'application_context' => [
                        'user_action' => 'CONTINUE',
                        'return_url' => route('user.subscriptions.change-package.complete', [
                            'subscription' => $subscription->id
                        ]),
                        'cancel_url' => route('profile.billing'),
                    ]
                ]);

                if ($response) {
                    $ends_at = $subscription->ends_at;

                    if (isset($invoice['next_bill_date'])) {
                        $ends_at = $invoice['next_bill_date']->toDateTimeString();
                    }

                    $subscription->addMeta('ends_at', $ends_at);

                    $url = collect($response->links)->first(function ($link) {
                        return $link->rel === 'approve';
                    });

                    if ($url) {
                        return $url->href;
                    }
                }
            }
        } else {
            $total = $price->getUnitPrice(false);

            $payload = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $price->currency,
                            'value' => number_format($total, '2', '.', '')
                        ]
                    ]
                ],
                'application_context' => [
                    'user_action' => 'CONTINUE',
                    'return_url' => route('user.subscriptions.change-package.complete', [
                        'subscription' => $subscription->id
                    ]),
                    'cancel_url' => route('profile.billing'),
                ],
            ];

            $response = $this->api->checkout->create($payload);
            if ($response && $response->url) {
                return $response->url;
            }
        }

        return null;
    }

    public function completeChangePrice(
        Subscription $subscription,
        PackagePrice $price,
        $attributes = []
    ) {
        $subscription->cancel('now');
        if ($price->isRecurring()) {
            $this->api->subscriptions->activate($attributes['subscription_id']);

            $gatewaySubscription = $this->api->subscriptions
                ->retrieve($attributes['subscription_id']);
            if (
                $gatewaySubscription
                && isset($gatewaySubscription->status)
                && $gatewaySubscription->status === 'ACTIVE'
            ) {
                $ends_at = null;

                if ($price->isRecurring()) {
                    $ends_at = now()->add(
                        Str::lower($price->term->interval),
                        $price->term->interval_count
                    )->format('Y-m-d H:i:s');
                    if ($gatewaySubscription->billing_info->next_billing_time) {
                        $ends_at = Carbon::create(
                            $gatewaySubscription->billing_info->next_billing_time
                        )
                            ->addDay()
                            ->format('Y-m-d H:i:s');
                    }
                }

                $gatewaySubscription->ends_at = $ends_at;

                $payload = [
                    'ref_profile_id' => $gatewaySubscription->id,
                    'recurring' => 1,
                    'gateway' => $this->gateway,
                    'name' => 'main',
                    'trial_ends_at' => null,
                    'starts_at' => now()->format('Y-m-d H:i:s'),
                    'ends_at' => $ends_at,
                    'cancels_at' => null,
                    'canceled_at' => null,
                    'meta' => json_encode($gatewaySubscription)
                ];

                if (isset($gatewaySubscription->billing_info->last_payment)) {
                    $payload['payment'] = [
                        'transaction_id' => $gatewaySubscription->billing_info->last_payment->time,
                        'currency' => $gatewaySubscription->billing_info->last_payment->amount->currency_code,
                        'total' => $gatewaySubscription->billing_info->last_payment->amount->value
                    ];
                }

                return $payload;
            }
        } else {
            $response = $this->api->checkout->approve($attributes['token']);

            if ($response) {
                $payment = $response->purchase_units[0]->payments->captures[0];
                return [
                    'ref_profile_id' => (string) Str::uuid(),
                    'name' => 'main',
                    'gateway' => $this->gateway,
                    'recurring' => 0,
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
        }

        return null;
    }
}
