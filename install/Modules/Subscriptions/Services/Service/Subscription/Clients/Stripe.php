<?php

namespace Modules\Subscriptions\Services\Service\Subscription\Clients;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Modules\Cashier\Facades\Cashier;
use Illuminate\Support\Facades\Storage;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\Subscription;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Subscriptions\Events\SubscriptionCreated;
use Modules\Subscriptions\Services\Service\Subscription\SubscriptionServiceClient;

class Stripe extends SubscriptionServiceClient
{
    protected $gateway = 'stripe';

    public function retrieve($id)
    {
        return $this->api->subscriptions->retrieve($id);
    }

    public function checkout(User $user, PackagePrice $price, $discount = null)
    {
        $gatewayPrice = $price->gatewayPrices()->where('gateway', $this->gateway)->first();

        $payload = [
            'success_url' => route('user.packages.subscriptions.store', [
                'payment_method' => $this->gateway
            ]) . '&subscription_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('user.pay'),
            'payment_method_types' => ['card'],
            'billing_address_collection' => 'required',
            'mode' => 'subscription',
            'metadata' => [
                'user' => $user->id
            ]
        ];

        if ($discount) {
            $gatewayPackage = $price->package->gatewayPackages()->where('gateway', $this->gateway)->first();
            $payload['line_items'] = [
                [
                    'price_data' => [
                        'currency' => $price->currency,
                        'product' => $gatewayPackage->product_id,
                        'unit_amount_decimal' => Cashier::amountCents($discount['amount']),
                        'recurring' => [
                            'interval' => $price->term->interval,
                            'interval_count' => $price->term->interval_count
                        ]
                    ],
                    'quantity' => 1,
                    'tax_rates' => ['txr_1LL1IHA7JNJTkW8p6SAdqNnK'],
                ]
            ];
        } else {
            $payload['line_items'] = [
                [
                    'price' => $gatewayPrice->price_id,
                    'quantity' => 2,
                    'tax_rates' => ['txr_1LL1IHA7JNJTkW8p6SAdqNnK'],
                ]
            ];
        }

        if ($price->trial_days_count) {
            $payload['subscription_data'] = [
                'trial_period_days' => $price->trial_days_count
            ];
        }

        $response = $this->api->checkout->create($payload);
        if ($response) {
            $response->href = $response->url;
        }

        return $response;
    }

    public function complete(User $user, $id, $payload)
    {
        $session = $this->api->checkout->retrieve($id);
        if ($session) {
            $subscription = $this->api->subscriptions->retrieve($session->subscription);

            if ($subscription && !$user->subscriptionExist($subscription->id, $this->gateway)) {
                $trial_ends_at = null;
                if ($subscription->trial_end) {
                    $trial_ends_at = Carbon::createFromTimestamp($subscription->trial_end)
                        ->format('Y-m-d H:i:s');
                }

                $starts_at = now()->format('Y-m-d H:i:s');
                if ($subscription->current_period_start) {
                    $starts_at = Carbon::createFromTimestamp($subscription->current_period_start)->format('Y-m-d H:i:s');
                }

                $ends_at = null;
                if ($subscription->current_period_end) {
                    $ends_at = Carbon::createFromTimestamp($subscription->current_period_end)->format('Y-m-d H:i:s');
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

            $payload = [
                'success_url' => route('user.subscriptions.change-package.complete', [
                    'subscription' => $subscription->id
                ]) . '?subscription_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('profile.billing'),
                'payment_method_types' => ['card'],
                'mode' => 'subscription',
                'line_items' => [
                    [
                        'price' => $planId,
                        'quantity' => 1
                    ]
                ]
            ];

            $response = $this->api->checkout->create($payload);

            if ($response) {
                return $response->url ?? null;
            }
        } else {
            $total = $price->getUnitPrice(false);

            $payload = [
                'success_url' => route('user.subscriptions.change-package.complete', [
                    'subscription' => $subscription->id
                ]) . '?subscription_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('profile.billing'),
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => Str::lower($price->currency),
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
        $params = []
    ) {
        $subscription->cancel('now');
        $session = $this->api->checkout->retrieve($params['subscription_id']);

        if ($session) {
            if ($price->isRecurring()) {
                $stripeSubscription = $this->api->subscriptions->retrieve($session->subscription);
                if ($stripeSubscription) {
                    $ends_at = null;
                    if ($stripeSubscription->current_period_end) {
                        $ends_at = Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                            ->format('Y-m-d H:i:s');
                    }

                    $payload = [
                        'ref_profile_id' => $stripeSubscription->id,
                        'recurring' => 1,
                        'gateway' => $this->gateway,
                        'name' => 'main',
                        'trial_ends_at' => null,
                        'starts_at' => now()->format('Y-m-d H:i:s'),
                        'ends_at' => $ends_at,
                        'cancels_at' => null,
                        'canceled_at' => null,
                        'meta' => json_encode($stripeSubscription)
                    ];

                    if (isset($stripeSubscription->latest_invoice)) {
                        $invoice = $this->api->invoices
                            ->retrieve($stripeSubscription->latest_invoice);
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
            } else {
                if ($session->payment_status === 'paid') {
                    return [
                        'ref_profile_id' => (string) Str::uuid(),
                        'name' => 'main',
                        'gateway' => $this->gateway,
                        'recurring' => 0,
                        'trial_ends_at' => null,
                        'starts_at' => now()->format('Y-m-d H:i:s'),
                        'ends_at' => null,
                        'meta' => json_encode($session),
                        'payment' => [
                            'transaction_id' => $session->payment_intent,
                            'currency' => Str::upper($session->currency),
                            'total' => normalize_amount($session->amount_total)
                        ]
                    ];
                }
            }
        }

        return null;
    }
}
