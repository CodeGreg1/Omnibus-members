<?php

namespace Modules\Subscriptions\Services\Service\WebhookHandler\Clients;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Modules\Payments\States\Paid;
use Modules\Carts\Models\Checkout;
use Modules\Payments\Services\Payment;
use Modules\Payments\Events\PaymentFailed;
use Modules\Payments\Events\PaymentCreated;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\Subscription;
use Modules\Subscriptions\Events\SubscriptionCreated;
use Modules\Subscriptions\Events\SubscriptionExpired;
use Modules\Subscriptions\Events\SubscriptionCancelled;
use Modules\Subscriptions\Events\SubscriptionPaymentFailed;
use Modules\Subscriptions\Events\SubscriptionPaymentCompleted;

class Razorpay
{
    protected $gateway = 'razorpay';

    /**
     * Handle subscription activated.
     *
     * @param array $payload
     */
    public function handleSubscriptionActivated(array $payload)
    {
        $subscriptionData = $payload['payload']['subscription']['entity'];
        $paymentData = $payload['payload']['payment']['entity'];
        if (isset($subscriptionData['notes']['checkoutId'])) {
            $checkout = Checkout::find($subscriptionData['notes']['checkoutId']);
            if (
                $checkout
                && !$checkout->customer->subscriptionExist($subscriptionData['id'], $this->gateway)
            ) {
                $lineItem = $checkout->lineItems->first();
                $trial_ends_at = null;
                if ($lineItem->checkoutable->trial_days_count) {
                    $trial_ends_at = now()->add(
                        'day',
                        $lineItem->checkoutable->trial_days_count
                    )->format('Y-m-d H:i:s');
                }

                $startsAt = Carbon::createFromTimestamp($subscriptionData['current_start']);
                $endsAt = $startsAt->add(
                    $lineItem->checkoutable->term->interval,
                    $lineItem->checkoutable->term->interval_count
                )
                    ->format('Y-m-d H:i:s');

                $subscription = $checkout->customer->subscriptions()->create([
                    'ref_profile_id' => $subscriptionData['id'],
                    'gateway' => $this->gateway,
                    'name' => 'main',
                    'recurring' => $lineItem->checkoutable->isRecurring() ? 1 : 0,
                    'trialing' => $lineItem->checkoutable->trial_days_count ? 1 : 0,
                    'trial_ends_at' => $trial_ends_at,
                    'starts_at' => $startsAt->format('Y-m-d H:i:s'),
                    'ends_at' => $endsAt,
                    'meta' => json_encode($subscriptionData)
                ]);

                $subscription->item()->create([
                    'package_price_id' => $lineItem->checkoutable->id,
                    'currency' => $lineItem->checkoutable->currency,
                    'total' => $checkout->getTotal(
                        false,
                        $lineItem->checkoutable->currency
                    ),
                ]);

                if ($subscription && $checkout->hasDiscount()) {
                    $subscription->discount()->create([
                        'subscription_id' => $subscription->id,
                        'promo_code_id' => $checkout->promoCode->id,
                        'value' => $checkout->promoCode->coupon->amount,
                        'type' => $checkout->promoCode->coupon->amount_type,
                        'amount' => $checkout->getDiscountPrice(
                            false,
                            $checkout->promoCode->coupon->currency
                        )
                    ]);
                }

                if ($paymentData) {
                    $payment = Payment::make(
                        $subscription,
                        [
                            'transaction_id' => $paymentData['id'],
                            'currency' => $paymentData['currency'],
                            'state' => 'paid',
                            'total' => normalize_amount($paymentData['amount']),
                            'gateway' => $checkout->gateway,
                            'meta' => $paymentData
                        ]
                    );

                    if ($subscription && $payment) {
                        $subscription = $subscription->fresh();
                        PaymentCompleted::dispatch($subscription, $payment);
                        SubscriptionPaymentCompleted::dispatch($subscription);
                    }
                }

                $checkout->checkouted_type = get_class($subscription);
                $checkout->checkouted_id = $subscription->id;
                $checkout->save();

                SubscriptionCreated::dispatch($subscription);
            }
        }

        if (
            isset($subscriptionData['notes']['subscriptionId'])
            && isset($subscriptionData['notes']['priceId'])
        ) {
            $subscription = Subscription::find($subscriptionData['notes']['subscriptionId']);
            $price = PackagePrice::find($subscriptionData['notes']['priceId']);
            if ($subscription && $price) {
                $cancelled = $subscription->cancel('now');
                if ($cancelled) {
                    $startsAt = Carbon::createFromTimestamp($subscriptionData['current_start']);
                    $endsAt = $startsAt->add(
                        $price->term->interval,
                        $price->term->interval_count
                    )->format('Y-m-d H:i:s');

                    $subscription = $subscription->subscribable->subscriptions()->create([
                        'ref_profile_id' => $subscriptionData['id'],
                        'gateway' => $this->gateway,
                        'name' => 'main',
                        'recurring' => $price->isRecurring() ? 1 : 0,
                        'trialing' => 0,
                        'trial_ends_at' => null,
                        'starts_at' => $startsAt->format('Y-m-d H:i:s'),
                        'ends_at' => $endsAt,
                        'meta' => json_encode($subscriptionData)
                    ]);

                    $subscription->item()->create([
                        'package_price_id' => $price->id,
                        'currency' => $price->currency,
                        'total' => $price->price,
                    ]);

                    if ($paymentData) {
                        $payment = Payment::make(
                            $subscription,
                            [
                                'transaction_id' => $paymentData['id'],
                                'currency' => $paymentData['currency'],
                                'state' => 'paid',
                                'total' => normalize_amount($paymentData['amount']),
                                'gateway' => $this->gateway,
                                'meta' => $paymentData
                            ]
                        );

                        if ($subscription && $payment) {
                            $subscription = $subscription->fresh();
                            PaymentCompleted::dispatch($subscription, $payment);
                            SubscriptionPaymentCompleted::dispatch($subscription);
                        }
                    }

                    SubscriptionCreated::dispatch($subscription);
                }
            }
        }
    }

    /**
     * Handle subscription charged.
     *
     * @param array $payload
     */
    public function handleSubscriptionCharged(array $payload)
    {
        $subscriptionData = $payload['payload']['subscription']['entity'];
        $paymentData = $payload['payload']['payment']['entity'];

        $subscription = Subscription::where([
            'ref_profile_id' => $subscriptionData['id'],
            'gateway' => $this->gateway
        ])->first();

        if ($subscription && $paymentData) {
            $startsAt = Carbon::createFromTimestamp($subscriptionData['current_start']);
            $subscription->starts_at = $startsAt->format('Y-m-d H:i:s');
            if ($subscriptionData['current_end']) {
                $subscription->ends_at = $startsAt->add(
                    $subscription->item->price->term->interval,
                    $subscription->item->price->term->interval_count
                )->format('Y-m-d H:i:s');
            } else {
                $subscription->ends_at = null;
            }

            $subscription->save();
            $subscription = $subscription->fresh();

            $paid = false;
            $payment = Payment::whereFirst($subscription, [
                'transaction_id' => $paymentData['id']
            ]);

            if ($payment) {
                $paid = $payment->state->getValue() === 'paid';
                if ($payment->state->getValue() !== 'paid') {
                    $payment->state->transitionTo(Paid::class);
                    $payment = $payment->fresh();
                }
            } else {
                $payment = Payment::make($subscription, [
                    'transaction_id' => $paymentData['id'],
                    'currency' => Str::upper($paymentData['currency']),
                    'gateway' => $this->gateway,
                    'total' => normalize_amount($paymentData['amount']),
                    'meta' => $paymentData
                ]);
            }

            if (!$paid && $payment) {
                PaymentCreated::dispatch($subscription, $payment);
                SubscriptionPaymentCompleted::dispatch($subscription);
            }
        }
    }

    /**
     * Handle invoice payment failed.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handlePaymentFailed(array $payload)
    {
        $subscriptionData = $payload['payload']['subscription']['entity'];
        $paymentData = $payload['payload']['payment']['entity'];
        if ($subscriptionData && $paymentData) {
            $subscription = Subscription::where([
                'ref_profile_id' => $subscriptionData['id'],
                'gateway' => $this->gateway
            ])->first();

            if ($subscription) {
                $payment = null;
                $isFailed = true;
                $latesPayment = $subscription->payables()->latest()->first();

                if ($latesPayment) {
                    if ($latesPayment->state->getValue() !== 'failed') {
                        $payment = Payment::whereFirst($subscription, [
                            'transaction_id' => $paymentData['id']
                        ]);

                        if ($payment) {
                            $payment->state->transitionTo(Failed::class);
                            $payment = $payment->fresh();
                        } else {
                            $payment = Payment::make($subscription, [
                                'transaction_id' => $paymentData['id'],
                                'currency' => Str::upper($paymentData['currency']),
                                'gateway' => $this->gateway,
                                'total' => normalize_amount($paymentData['amount']),
                                'meta' => $paymentData,
                                'state' => 'failed'
                            ]);
                        }
                    } else {
                        $isFailed = false;
                    }
                } else {
                    $payment = Payment::make($subscription, [
                        'transaction_id' => $paymentData['id'],
                        'currency' => Str::upper($paymentData['currency']),
                        'gateway' => $this->gateway,
                        'total' => normalize_amount($paymentData['amount']),
                        'meta' => $paymentData,
                        'state' => 'failed'
                    ]);
                }

                if ($payment) {
                    if ($isFailed) {
                        PaymentFailed::dispatch($subscription, $payment);
                        SubscriptionPaymentFailed::dispatch($subscription);
                    }

                    if (
                        $subscription->getStatus() === 'ended'
                        && $payment->state->getValue() === 'failed'
                        && is_null($subscription->canceled_at)
                    ) {
                        $result = $subscription->cancel('now', false);
                        if ($result) {
                            $subscription->fill([
                                'cancels_at_end' => 1
                            ])->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * Handle subscription completed.
     *
     * @param array $payload
     */
    public function handleSubscriptionCompleted(array $payload)
    {
        $subscriptionData = $payload['payload']['subscription']['entity'];

        $subscription = Subscription::where([
            'ref_profile_id' => $subscriptionData['id'],
            'gateway' => $this->gateway
        ])->first();

        if ($subscription) {
            $subscription->skipTrial()->markAsExpired();
            SubscriptionExpired::dispatch($subscription);
        }
    }

    /**
     * Handle subscription updated.
     *
     * @param array $payload
     */
    public function handleSubscriptionUpdated(array $payload)
    {
        $subscriptionData = $payload['payload']['subscription']['entity'];

        $subscription = Subscription::where([
            'ref_profile_id' => $subscriptionData['id'],
            'gateway' => $this->gateway
        ])->first();

        if ($subscription) {
            $startsAt = Carbon::createFromTimestamp($subscriptionData['current_start']);
            $subscription->starts_at = $startsAt->format('Y-m-d H:i:s');
            if ($subscriptionData['current_end']) {
                $subscription->ends_at = $startsAt->add(
                    $subscription->item->price->term->interval,
                    $subscription->item->price->term->interval_count
                )->format('Y-m-d H:i:s');
            } else {
                $subscription->ends_at = null;
            }

            if ($subscriptionData['paid_count'] === 1) {
                $subscription->trial_ends_at = null;
            }

            $subscription->save();
        }
    }

    /**
     * Handle subscription cancelled.
     *
     * @param array $payload
     */
    public function handleSubscriptionCancelled(array $payload)
    {
        $subscriptionData = $payload['payload']['subscription']['entity'];

        $subscription = Subscription::where([
            'ref_profile_id' => $subscriptionData['id'],
            'gateway' => $this->gateway
        ])->first();

        if ($subscription) {
            if ($subscription->cancels_at_end) {
                $subscription->skipTrial()->markAsExpired();
                SubscriptionExpired::dispatch($subscription);
            } else {
                $subscription->skipTrial()->markAsCanceled();
                SubscriptionCancelled::dispatch($subscription);
            }
        }
    }

    /**
     * Handle subscription halted.
     *
     * @param array $payload
     */
    public function handleSubscriptionHalted(array $payload)
    {
    }
}
