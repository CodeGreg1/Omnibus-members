<?php

namespace Modules\Subscriptions\Services\Concerns;

use Modules\Subscriptions\Models\Coupon;
use Modules\Subscriptions\Models\PromoCode;

trait ManageCoupons
{
    /**
     * The promo codes that belong to the user.
     */
    public function promoCodes()
    {
        return $this->belongsToMany(PromoCode::class)->withTimestamps();
    }

    /**
     * The coupons that belong to the user.
     */
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class)->withTimestamps();
    }
}