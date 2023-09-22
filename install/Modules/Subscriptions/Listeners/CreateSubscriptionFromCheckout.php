<?php

namespace Modules\Subscriptions\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Payments\Services\Payment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Subscriptions\Events\SubscriptionCreated;
use Modules\Subscriptions\Events\SubscriptionPaymentCompleted;

class CreateSubscriptionFromCheckout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (!$event->checkout->customer->subscriptionExist($event->payload['ref_profile_id'], $event->checkout->gateway)) {
            $subscription = DB::transaction(function () use ($event) {
                $latestSubscription = $event->checkout->customer->latestSubscription()->first();
                if ($latestSubscription && $latestSubscription->gateway === 'manual') {
                    $latestSubscription->cancel('now');
                }

                $lineItem = $event->checkout->lineItems->first();
                $payment = null;
                if (
                    $lineItem->checkoutable->isRecurring()
                    && is_null($event->payload['ends_at'])
                ) {
                    $event->payload['ends_at'] = Carbon::create($event->payload['starts_at'])
                        ->add(
                            $lineItem->checkoutable->term->interval,
                            $lineItem->checkoutable->term->interval_count
                        )->format('Y-m-d H:i:s');
                }

                if (
                    $event->payload['trial_ends_at'] === 1
                    && $lineItem->checkoutable->trial_days_count
                ) {
                    $event->payload['trial_ends_at'] = Carbon::create(
                        $event->payload['starts_at']
                    )
                        ->addDays($lineItem->checkoutable->trial_days_count)
                        ->format('Y-m-d H:i:s');
                    $event->payload['ends_at'] = $event->payload['trial_ends_at'];
                }

                $subscription = $event->checkout->customer
                    ->subscriptions()
                    ->create(array_merge([
                        'gateway' => $event->checkout->gateway,
                        'recurring' => $lineItem->checkoutable->isRecurring() ? 1 : 0,
                        'note' => $event->checkout->getMetadata('note'),
                        'trialing' => !!$event->payload['trial_ends_at']
                    ], $event->payload));

                if (isset($event->payload['item'])) {
                    $subscription->item()->create(array_merge([
                        'package_price_id' => $lineItem->checkoutable->id
                    ], $event->payload['item']));
                } else {
                    $subscription->item()->create([
                        'package_price_id' => $lineItem->checkoutable->id,
                        'currency' => $lineItem->checkoutable->currency,
                        'total' => $event->checkout->getTotal(
                            false,
                            $lineItem->checkoutable->currency
                        ),
                    ]);
                }

                if ($subscription && $event->checkout->hasDiscount()) {
                    $subscription->discount()->create([
                        'subscription_id' => $subscription->id,
                        'promo_code_id' => $event->checkout->promoCode->id,
                        'value' => $event->checkout->promoCode->coupon->amount,
                        'type' => $event->checkout->promoCode->coupon->amount_type,
                        'amount' => $event->checkout->getDiscountPrice(
                            false,
                            $event->checkout->promoCode->coupon->currency
                        )
                    ]);
                }

                if (isset($event->payload['payment'])) {
                    $payment = Payment::make($subscription, array_merge(
                        $event->payload['payment'],
                        [
                            'gateway' => $event->checkout->gateway,
                        ]
                    ));
                }

                if ($subscription && $payment) {
                    PaymentCompleted::dispatch($subscription, $payment);
                    SubscriptionPaymentCompleted::dispatch($subscription);
                }

                return $subscription;
            }, 3);

            if ($subscription) {
                $event->checkout->checkouted_type = get_class($subscription);
                $event->checkout->checkouted_id = $subscription->id;
                $event->checkout->save();

                SubscriptionCreated::dispatch($subscription);
            }
        }
    }
}