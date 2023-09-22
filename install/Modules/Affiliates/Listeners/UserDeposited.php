<?php

namespace Modules\Affiliates\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Affiliates\Events\AffiliateCommissionCreated;

class UserDeposited
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

        $commissionType = commission_type()->get('deposit_commission');
        if (!$commissionType) {
            return;
        }

        if (!$commissionType['active']) {
            return;
        }

        $totalLevelsCount = count($commissionType['levels']);

        $user = $event->deposit->user;
        $referredUser = $user;
        $i = 0;
        do {
            $referral = $user->referrerAffiliate;
            if (!$referral) {
                $totalLevelsCount = 0;
            } else {
                if (
                    $referral->affiliate->active
                    && $referral->affiliate->allow_deposit_commission
                ) {
                    $rate = $commissionType['levels'][$i];
                    $amount = number_format((($rate / 100) * $event->deposit->amount), 2);
                    $commission = $referral->affiliate->addCommission(
                        $referredUser,
                        'Deposit commission',
                        $event->deposit->currency,
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
