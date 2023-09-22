<?php

namespace Modules\Affiliates\Traits;

trait AffiliateConversionRate
{
    protected function getAffiliateConversionRate($affiliate)
    {
        if (!$affiliate) {
            return 0;
        }

        $registerredCount = $affiliate->referrals()->count();

        if (!$affiliate->total_clicks) {
            return 0;
        }

        if ($registerredCount > $affiliate->total_clicks) {
            return 100;
        }

        return real_number(number_format((($registerredCount / $affiliate->total_clicks) * 100), 2));
    }
}