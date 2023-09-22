<?php

namespace Modules\Affiliates\Traits;

use Illuminate\Support\Str;
use Modules\Affiliates\Models\AffiliateUser;
use Modules\Affiliates\Models\AffiliateReferral;

trait IsAffiliate
{
    public function affiliate()
    {
        return $this->hasOne(AffiliateUser::class);
    }

    public function referrerAffiliate()
    {
        return $this->hasOne(AffiliateReferral::class, 'reffered_id');
    }

    /**
     * check if uesr has any affiliate
     *
     * @return bool
     */
    public function hasAffiliate()
    {
        return !!$this->affiliate()->count();
    }

    public function isAffiliateUser()
    {
        if (!$this->hasAffiliate()) {
            return false;
        }

        return !!$this->affiliate->active;
    }

    public function generateAffiliateCode($type = 'string')
    {
        $code = $this->username;
        if (filter_var($code, FILTER_VALIDATE_EMAIL)) {
            $code = strstr($code, '@', true);
        }

        $code = preg_replace('/[^A-Za-z0-9\-]/', '', $code);

        if ($type === 'number') {
            $code = substr(number_format(time() * rand(), 0, '', ''), 0, 10);
        }

        if ($type === 'string') {
            $code = Str::random(10);
        }

        return $code;
    }
}