<?php

namespace Modules\Subscriptions\Services\Service\WebhookHandler\Clients;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Payments\States\Paid;
use Modules\Payments\States\Failed;
use Modules\Payments\Services\Payment;
use Modules\Payments\Events\PaymentFailed;
use Modules\Payments\Events\PaymentCreated;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Subscriptions\Models\Subscription;
use Modules\Subscriptions\Events\SubscriptionExpired;
use Modules\Subscriptions\Events\SubscriptionCancelled;
use Modules\Subscriptions\Events\SubscriptionPaymentFailed;
use Modules\Subscriptions\Events\SubscriptionPaymentCompleted;
use Modules\Subscriptions\Facades\Subscription as SubscriptionFacade;

class Paypal
{
    protected $gateway = 'paypal';

    /**
     * Handle user subscription updated.
     *
     * @param array $payload
     */
    public function handleBillingSubscriptionUpdated($payload)
    {
        $subscription = Subscription::where([
            'ref_profile_id' => $payload['resource']['id'],
            'gateway' => $this->gateway
        ])->first();

        if ($subscription) {
            $data = $payload['resource'];

            $nextBilling = null;
            if (isset($data['billing_info']['next_billing_time'])) {
                $nextBilling = Carbon::parse($data['billing_info']['next_billing_time'])->format('Y-m-d H:i:s');
                $subscription->ends_at = $nextBilling;
            }

            if (isset($data['billing_info']['cycle_executions'])) {
                if ($data['billing_info']['cycle_executions'][0]['tenure_type'] === 'TRIAL') {
                    $data['status'] = 'TRIALING';

                    if ($nextBilling && !$subscription->trial_ends_at || $subscription->trial_ends_at->ne($nextBilling)) {
                        $subscription->trial_ends_at = $nextBilling;
                    }
                }
            }

            $subscription->price = null;
            if (isset($data['billing_info']['last_payment'])) {
                $subscription->price = floatval($data['billing_info']['last_payment']['amount']['value']) * 100;
            }

            if (isset($data['agreement_details']) && $data['agreement_details']['last_payment_date'] && $subscription->isRecurring()) {
                $subscription->starts_at = $subscription->ends_at;
                $subscription->ends_at = Carbon::parse($data['agreement_details']['last_payment_date'])
                    ->add(
                        $subscription->item->price->term->interval,
                        $subscription->item->price->term->interval_count
                    )->format('Y-m-d H:i:s');
            }

            $subscription->save();
        }
    }

    /**
     * Handle user subscription cancelled.
     *
     * @param array $payload
     */
    public function handleBillingSubscriptionCancelled($payload)
    {
        $subscription = Subscription::where([
            'ref_profile_id' => $payload['resource']['id'],
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
     * Handle user subscription expired.
     *
     * @param array $payload
     */
    public function handleBillingSubscriptionExpired($payload)
    {
        $subscription = Subscription::where([
            'ref_profile_id' => $payload['resource']['id'],
            'gateway' => $this->gateway
        ])->first();

        if ($subscription) {
            $subscription->skipTrial()->markAsExpired();

            SubscriptionExpired::dispatch($subscription);
        }
    }

    /**
     * Handle payment sale completed.
     *
     * @param array $payload
     */
    public function handlePaymentSaleCompleted($payload)
    {
        $data = $payload['resource'];
        if (isset($data['billing_agreement_id'])) {
            $subscription = Subscription::where([
                'ref_profile_id' => $data['billing_agreement_id'],
                'gateway' => $this->gateway
            ])->first();

            if ($subscription) {
                $payment = $subscription->payables()->first();
                $intervalPaid = $subscription->payables()->where('created_at', '>=', now()->subHours(10))->count();
                if (
                    $subscription->payables()->count() === 1
                    && $intervalPaid
                    && $payment->meta === '{}'
                ) {
                    $payment->transaction_id = $data['id'];
                    $payment->meta = $data;
                    $payment->save();
                } else {
                    if ($subscription->isRecurring()) {
                        $date = Carbon::parse($data['update_time']);
                        $ends_at = $date->add(
                            $subscription->item->price->term->interval,
                            $subscription->item->price->term->interval_count
                        )->format('Y-m-d H:i:s');

                        $subscription->cancels_at = null;
                        $subscription->canceled_at = null;
                        $subscription->starts_at = $date->format('Y-m-d H:i:s');
                        $subscription->ends_at = $ends_at;
                        $subscription->save();

                        $subscription = $subscription->fresh();
                    }

                    if (!$intervalPaid) {
                        $payment = Payment::make($subscription, [
                            'transaction_id' => $data['id'],
                            'currency' => $data['amount']['currency'],
                            'gateway' => $this->gateway,
                            'total' => $data['amount']['total'],
                            'meta' => $data
                        ]);

                        PaymentCompleted::dispatch($subscription, $payment);
                        SubscriptionPaymentCompleted::dispatch($subscription);
                    }
                }
            }
        }
    }

    /**
     * Handle user subscription payment failed.
     *
     * @param array $payload
     */
    public function handleBillingSubscriptionPaymentFailed($payload)
    {
        $subscription = Subscription::where([
            'ref_profile_id' => $payload['resource']['id'],
            'gateway' => $this->gateway
        ])->first();

        if ($subscription) {
            $payment = null;
            $latesPayment = $subscription->payables()->latest()->first();
            if ($latesPayment->state->getValue() === 'paid') {
                $payment = Payment::make($subscription, [
                    'transaction_id' => Str::uuid(),
                    'currency' => $subscription->item->price->currency,
                    'gateway' => $this->gateway,
                    'total' => $subscription->getTotal(false),
                    'meta' => $payload['resource'],
                    'state' => 'failed'
                ]);
            }

            if ($payment) {
                PaymentFailed::dispatch($subscription, $payment);
                SubscriptionPaymentFailed::dispatch($subscription);
            }

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
    }
}
