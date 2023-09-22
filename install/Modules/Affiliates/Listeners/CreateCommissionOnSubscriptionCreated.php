<?php

namespace Modules\Affiliates\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Affiliates\Events\AffiliateCommissionCreated;

class CreateCommissionOnSubscriptionCreated
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
        if (setting('allow_affiliates') !== 'enable') {
            return;
        }

        $payments = $event->subscription->payables()->get();
        if (!$payments->count()) {
            return;
        }

        $commissionType = commission_type()->get('subscription_commission');
        if (!$commissionType) {
            return;
        }

        if (!$commissionType['active']) {
            return;
        }

        if (
            commission_type()->hasCondition('subscription_commission', 'first_billing_only')
            && $payments->count() > 1
        ) {
            return;
        }

        $totalLevelsCount = count($commissionType['levels']);

        $payment = $payments->sortByDesc('id')->first();

        $user = $event->subscription->subscribable;
        $referredUser = $user;
        $i = 0;
        do {
            $referral = $user->referrerAffiliate;
            if (!$referral) {
                $totalLevelsCount = 0;
            } else {
                if (
                    $referral->affiliate->active
                    && $referral->affiliate->allow_subscription_commission
                ) {
                    $rate = $commissionType['levels'][$i];
                    $amount = number_format((($rate / 100) * $payment->total), 2);
                    $commission = $referral->affiliate->addCommission(
                        $referredUser,
                        'Subscription commission',
                        $payment->currency,
                        $amount,
                        $rate
                    );
                    event(new AffiliateCommissionCreated($commission));
                }

                $user = $referral->affiliate->user;
                $totalLevelsCount--;
            }
            $i++;
        } while ($totalLevelsCount > 0);
    }
}
