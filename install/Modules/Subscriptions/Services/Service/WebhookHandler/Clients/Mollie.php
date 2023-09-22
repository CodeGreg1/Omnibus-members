<?php

namespace Modules\Subscriptions\Services\Service\WebhookHandler\Clients;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Modules\Cashier\Facades\Cashier;
use Modules\Payments\Services\Payment;
use Modules\Payments\Events\PaymentFailed;
use Modules\Payments\Events\PaymentCreated;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Subscriptions\Models\Subscription;
use Modules\Payments\Models\Payment as PaymentModel;
use Modules\Subscriptions\Events\SubscriptionExpired;
use Modules\Subscriptions\Events\SubscriptionCancelled;
use Modules\Subscriptions\Events\SubscriptionPaymentFailed;
use Modules\Subscriptions\Events\SubscriptionPaymentCompleted;

class Mollie
{
    protected $gateway = 'mollie';

    /**
     * Handle event.
     *
     * @param array $payload
     */
    public function handleEvent(array $payload)
    {
        if (isset($payload['id'])) {
            $service = Cashier::client($this->gateway);
            $paymentJson = $service->payments->retrieve($payload['id']);
            if ($paymentJson && isset($paymentJson->subscriptionId)) {
                $subscription = Subscription::where([
                    'ref_profile_id' => $paymentJson->subscriptionId,
                    'gateway' => $this->gateway
                ])->first();

                if ($subscription) {
                    $payment = PaymentModel::where([
                        'transaction_id' => $payload['id'],
                        'gateway' => $this->gateway
                    ])->first();

                    if ($paymentJson->status === 'paid') {
                        $status = 'paid';
                    } else if (in_array($paymentJson->status, ['expired', 'failed'])) {
                        $status = 'failed';
                    } else {
                        $status = 'pending';
                    }

                    $paid = false;
                    if ($payment) {
                        $paid = $payment->state->getValue() === 'paid';
                        $payment = tap($payment)->update([
                            'state' => $status
                        ]);
                    } else {
                        if ($status !== 'failed') {
                            $payment = Payment::make($subscription, [
                                'transaction_id' => $paymentJson->id,
                                'currency' => $paymentJson->amount->currency,
                                'gateway' => $this->gateway,
                                'total' => $paymentJson->amount->value,
                                'meta' => $paymentJson,
                                'state' => $status
                            ]);
                        } else {
                            $latesPayment = $subscription->payables()->latest()->first();
                            if ($latesPayment->state->getValue() === 'paid') {
                                $payment = Payment::make($subscription, [
                                    'transaction_id' => $paymentJson->id,
                                    'currency' => $paymentJson->amount->currency,
                                    'gateway' => $this->gateway,
                                    'total' => $paymentJson->amount->value,
                                    'meta' => $paymentJson,
                                    'state' => $status
                                ]);
                            }
                        }
                    }

                    $subscriptionJson = $service->subscriptions->retrieve(
                        $paymentJson->customerId,
                        $paymentJson->subscriptionId
                    );

                    if ($subscriptionJson && isset($subscriptionJson->id)) {
                        if ($status === 'paid') {
                            $endsAt = Carbon::parse($subscription->ends_at)
                                ->add(
                                    $subscription->item->price->term->interval,
                                    $subscription->item->price->term->interval_count
                                )->format('Y-m-d H:i:s');
                            // if (isset($subscriptionJson->nextPaymentDate)) {
                            //     $endsAt = Carbon::create($subscriptionJson->nextPaymentDate)
                            //         ->format('Y-m-d') . ' ' . now()->format('H:i:s');
                            // }

                            $subscription->starts_at = now()->format('Y-m-d H:i:s');
                            $subscription->ends_at = $endsAt;
                        }

                        if ($subscriptionJson->status === 'canceled') {
                            if ($subscription->cancels_at_end) {
                                $subscription->skipTrial()->markAsExpired();
                                SubscriptionExpired::dispatch($subscription);
                            } else {
                                $subscription->skipTrial()->markAsCanceled();
                                SubscriptionCancelled::dispatch($subscription);
                            }
                        }

                        if ($subscriptionJson->status === 'completed') {
                            $subscription->skipTrial()->markAsExpired();
                            SubscriptionExpired::dispatch($subscription);
                        }

                        $subscription->save();

                        $subscription = $subscription->fresh();
                    }

                    if ($payment) {
                        if ($status === 'paid' && !$paid) {
                            PaymentCompleted::dispatch($subscription, $payment);
                            SubscriptionPaymentCompleted::dispatch($subscription);
                        }
                        if ($status === 'failed') {
                            PaymentFailed::dispatch($subscription, $payment);
                            SubscriptionPaymentFailed::dispatch($subscription);

                            if (
                                $subscription->getStatus() === 'ended'
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
                        if ($status === 'pending') {
                            PaymentCreated::dispatch($subscription, $payment);
                        }
                    }
                }
            }
        }
    }
}
