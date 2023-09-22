<?php

namespace Modules\Affiliates\Listeners;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Affiliates\Models\AffiliateUser;
use Modules\Affiliates\Models\AffiliateReferral;
use Modules\Affiliates\Models\AffiliateReferralLevel;

class CreateAffiliateReferral
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
        if (Cookie::has('aff') && setting('allow_affiliates') === 'enable') {
            $code = Cookie::get('aff');
            $affiliate = AffiliateUser::where('code', $code)->first();
            if ($affiliate) {
                $referral = AffiliateReferral::create([
                    'affiliate_user_id' => $affiliate->id,
                    'reffered_id' => $event->user->id
                ]);

                // Save referral user by level
                $level = 1;
                $user = $event->user;
                $userId = $user->id;
                do {
                    AffiliateReferralLevel::create([
                        'affiliate_user_id' => $referral->affiliate->id,
                        'reffered_id' => $userId,
                        'level' => $level
                    ]);

                    $user = $referral->affiliate->user;
                    $referral = $user->referrerAffiliate;
                    $level++;
                } while ($referral);
            }

            Cookie::queue(
                Cookie::forget('aff')
            );
        }
    }
}
