<?php

namespace Modules\Subscriptions\Services\Service\WebhookHandler\Clients;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Payments\States\Paid;
use Modules\Payments\States\Failed;
use Modules\Cashier\Facades\Cashier;
use Modules\Payments\Services\Payment;
use Modules\Payments\Events\PaymentFailed;
use Modules\Payments\Events\PaymentCreated;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Subscriptions\Models\Subscription;
use Modules\Subscriptions\Events\SubscriptionExpired;
use Modules\Subscriptions\Events\SubscriptionCancelled;
use Modules\Subscriptions\Events\SubscriptionPaymentFailed;
use Modules\Subscriptions\Events\SubscriptionPaymentCompleted;

class Stripe
{
    protected $gateway = 'stripe';

    /**
     * Handle user subscription updated.
     *
     * @param array $payload
     */
    public function handleCustomerSubscriptionUpdated(array $payload)
    {
        $data = $payload['data']['object'];

        $subscription = Subscription::where([
            'ref_profile_id' => $data['id'],
            'gateway' => $this->gateway
        ])->first();

        if ($subscription) {
            // Trial ending date...
            if (isset($data['trial_end']) && $data['trial_end']) {
                $trialEnd = Carbon::createFromTimestamp($data['trial_end']);

                if (!$subscription->trial_ends_at || $subscription->trial_ends_at->ne($trialEnd)) {
                    $subscription->trial_ends_at = $trialEnd;
                } else {
                    $subscription->trial_ends_at = null;
                }
            }

            if (isset($data['cancel_at']) && $data['cancel_at']) {
                $subscription->cancels_at = Carbon::createFromTimestamp($data['cancel_at']);
            } else {
                $subscription->cancels_at = null;
            }

            if (isset($data['canceled_at']) && $data['canceled_at']) {
                $subscription->canceled_at = Carbon::createFromTimestamp($data['canceled_at']);
            } else {
                $subscription->canceled_at = null;
            }

            if ($data['status'] === 'unpaid') {
                $subscription->ended_at = Carbon::createFromTimestamp($data['ended_at']);
            }

            if ($data['status'] === 'canceled') {
                $subscription->ended_at = Carbon::createFromTimestamp($data['canceled_at']);
            }

            if ($data['status'] === 'trialing') {
                $trialing = 0;
                $trial_ends_at = null;
                $subscription->starts_at = Carbon::createFromTimestamp($data['current_period_start'])
                    ->format('Y-m-d H:i:s');
                $subscription->ends_at = Carbon::createFromTimestamp($data['current_period_end'])
                    ->format('Y-m-d H:i:s');
                if ($data['trial_end']) {
                    $trial_end_date = Carbon::createFromTimestamp($data['trial_end']);
                    if ($trial_end_date->isFuture()) {
                        $trialing = 1;
                        $trial_ends_at = $trial_end_date->format('Y-m-d H:i:s');
                    }
                }
                $subscription->trialing = $trialing;
                $subscription->trial_ends_at = $trial_ends_at;
            }

            $subscription->save();
            if ($data['status'] === 'active') {
                $subscription->trialing = 0;
                $subscription->trial_ends_at = null;
                $invoice = Cashier::client($this->gateway)
                    ->invoices->retrieve($data['latest_invoice']);
                if ($invoice && $invoice->paid) {
                    $subscription->starts_at = Carbon::createFromTimestamp($invoice->period_start)
                        ->format('Y-m-d H:i:s');
                    $subscription->ends_at = Carbon::createFromTimestamp($invoice->period_end)
                        ->format('Y-m-d H:i:s');

                    $payment = Payment::whereFirst($subscription, [
                        'transaction_id' => $data['latest_invoice']
                    ]);
                    $subscription->save();
                    $subscription = $subscription->fresh();
                    if ($payment) {
                        $paid = false;
                        if ($payment) {
                            if ($payment->state->getValue() !== 'paid') {
                                $paid = true;
                                $payment->state->transitionTo(Paid::class);
                                $payment = $payment->fresh();
                            }
                        } else {
                            $payment = Payment::make($subscription, [
                                'transaction_id' => $invoice->id,
                                'currency' => Str::upper($invoice->currency),
                                'gateway' => $this->gateway,
                                'total' => normalize_amount($invoice->amount_paid)
                            ]);
                        }

                        if (!$paid) {
                            PaymentCompleted::dispatch($subscription, $payment);
                            SubscriptionPaymentCompleted::dispatch($subscription);
                        }
                    }
                }
            }
        }
    }

    /**
     * Handle a canceled customer from a Stripe subscription.
     *
     * @param array $payload
     */
    public function handleCustomerSubscriptionDeleted(array $payload)
    {
        $data = $payload['data']['object'];

        $subscription = Subscription::where([
            'ref_profile_id' => $data['id'],
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
     * Handle invoice created.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleInvoiceCreated(array $payload)
    {
        $data = $payload['data']['object'];
        if (isset($data['subscription']) && $data['subscription']) {
            $subscription = Subscription::where([
                'ref_profile_id' => $data['subscription'],
                'gateway' => $this->gateway
            ])->first();
            if ($subscription) {
                $payment = null;
                $latesPayment = $subscription->payables()->latest()->first();
                if ($latesPayment) {
                    if ($latesPayment->state->getValue() === 'paid') {
                        $payment = Payment::make($subscription, [
                            'transaction_id' => $data['id'],
                            'currency' => Str::upper($data['currency']),
                            'gateway' => $this->gateway,
                            'total' => normalize_amount($data['amount_due']),
                            'meta' => $data,
                            'state' => 'pending'
                        ]);
                    } else {
                        if (
                            $latesPayment->state->getValue() === 'failed'
                            && $subscription->getStatus() === 'ended'
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
                } else {
                    $payment = Payment::make($subscription, [
                        'transaction_id' => $data['id'],
                        'currency' => Str::upper($data['currency']),
                        'gateway' => $this->gateway,
                        'total' => normalize_amount($data['amount_due']),
                        'meta' => $data,
                        'state' => 'pending'
                    ]);
                }

                if ($payment) {
                    PaymentCreated::dispatch($subscription, $payment);
                }
            }
        }
    }

    /**
     * Handle invoice paid.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleInvoicePaid(array $payload)
    {
        $data = $payload['data']['object'];
        if (isset($data['subscription']) && $data['subscription']) {
            $subscription = Subscription::where([
                'ref_profile_id' => $data['subscription'],
                'gateway' => $this->gateway
            ])->first();
            if ($subscription) {
                $paid = false;
                $payment = Payment::whereFirst($subscription, [
                    'transaction_id' => $data['id']
                ]);
                if ($payment) {
                    $paid = $payment->state->getValue() === 'paid';
                    if ($payment->state->getValue() !== 'paid') {
                        $payment->state->transitionTo(Paid::class);
                        $payment = $payment->fresh();
                    }
                } else {
                    $payment = Payment::make($subscription, [
                        'transaction_id' => $data['id'],
                        'currency' => Str::upper($data['currency']),
                        'gateway' => $this->gateway,
                        'total' => normalize_amount($data['amount_paid']),
                        'meta' => $data
                    ]);
                }

                if ($data['status'] === 'paid') {
                    $lineItem = $data['lines']['data'][0];
                    if ($lineItem) {
                        $startsAt = Carbon::createFromTimestamp($lineItem['period']['start'])
                            ->format('Y-m-d H:i:s');
                        $endsAt = Carbon::createFromTimestamp($lineItem['period']['end'])
                            ->format('Y-m-d H:i:s');
                    } else {
                        $startsAt = Carbon::createFromTimestamp($data['period_end'])->format('Y-m-d H:i:s');
                        $endsAt = Carbon::createFromTimestamp($data['period_end'])
                            ->add(
                                $subscription->item->price->term->interval,
                                $subscription->item->price->term->interval_count
                            )
                            ->format('Y-m-d H:i:s');
                    }
                    $subscription->starts_at = $startsAt;
                    $subscription->ends_at = $endsAt;
                    $subscription->save();
                    $subscription = $subscription->fresh();
                }

                if (!$paid && $payment) {
                    PaymentCompleted::dispatch($subscription, $payment);
                    SubscriptionPaymentCompleted::dispatch($subscription);
                }
            }
        }
    }

    /**
     * Handle invoice payment failed.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleInvoicePaymentFailed(array $payload)
    {
        $data = $payload['data']['object'];
        if (isset($data['subscription'])) {
            $subscription = Subscription::where([
                'ref_profile_id' => $data['subscription'],
                'gateway' => $this->gateway
            ])->first();

            if ($subscription) {
                $payment = null;
                $isFailed = true;
                $latesPayment = $subscription->payables()->latest()->first();
                if ($latesPayment) {
                    if ($latesPayment->state->getValue() !== 'failed') {
                        $payment = Payment::whereFirst($subscription, [
                            'transaction_id' => $data['id']
                        ]);

                        if ($payment) {
                            $payment->state->transitionTo(Failed::class);
                            $payment = $payment->fresh();
                        } else {
                            $payment = Payment::make($subscription, [
                                'transaction_id' => $data['id'],
                                'currency' => Str::upper($data['currency']),
                                'gateway' => $this->gateway,
                                'total' => normalize_amount($data['amount_due']),
                                'meta' => $data,
                                'state' => 'failed'
                            ]);
                        }
                    } else {
                        $isFailed = false;
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
                } else {
                    $payment = Payment::make($subscription, [
                        'transaction_id' => $data['id'],
                        'currency' => Str::upper($data['currency']),
                        'gateway' => $this->gateway,
                        'total' => normalize_amount($data['amount_due']),
                        'meta' => $data,
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
     * Handle invoice paid through charge.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleChargeSucceeded(array $payload)
    {
        $data = $payload['data']['object'];
        if (isset($data['invoice'])) {
            $payment = Payment::findByTransactionId($data['invoice']);
            if ($payment && $payment->state->getValue() !== 'paid') {
                $payment->state->transitionTo(Paid::class);
                $payment = $payment->fresh();
                $subscription = $payment->payable;

                PaymentCompleted::dispatch($subscription, $payment);
                SubscriptionPaymentCompleted::dispatch($subscription);
            }
        }
    }
}
