<?php

namespace Modules\Affiliates\Services;

use Illuminate\Support\Facades\DB;
use Modules\Affiliates\Models\AffiliateCommission;

class ApprovedCommissions
{
    public function get($affiliate)
    {
        if (!$affiliate) {
            return $this->defaultData();
        }

        $commissions = AffiliateCommission::where('affiliate_user_id', $affiliate->id)
            ->when(setting('allow_wallet_multi_currency') !== 'enable', function ($query) {
                $query->where('code', setting('currency'));
            })
            ->where('status', 'pending')
            ->where('approve_on_end', '<=', now())
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        if (!$commissions->count()) {
            $commissions = collect([setting('currency') => 0]);
        }

        return $commissions->map(function ($amount, $currency) {
            return currency_format($amount, $currency);
        });
    }

    protected function defaultData()
    {
        return collect([setting('currency') => currency_format(0, setting('currency'))]);
    }
}
