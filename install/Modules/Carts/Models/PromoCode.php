<?php

namespace Modules\Carts\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Camroncade\Timezone\Facades\Timezone;
use Modules\Cashier\Traits\CashierModeScope;
use Modules\Subscriptions\Models\SubscriptionCoupon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromoCode extends Model
{
    use HasFactory, CashierModeScope;

    protected $fillable = [
        "coupon_id",
        "code",
        "active",
        "expires_at",
        "max_redemptions",
        "times_redeemed",
        "live"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime'
    ];

    /**
     * Append modified field values
     */
    protected $appends = [
        'expires_at_display',
    ];

    protected $with = ['coupon'];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * The users that belong to the promo code.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The subscriptions that belong to the promo code.
     */
    public function subscriptions()
    {
        return $this->hasMany(SubscriptionCoupon::class, 'promo_code_id');
    }

    public function hasUser($user)
    {
        return $this->users()->where('users.id', $user)->exists();
    }

    /**
     * check if promo code has any subscriptions
     *
     * @return bool
     */
    public function hasSubscriptions()
    {
        return !!$this->subscriptions()->count();
    }

    /**
     * Get expires at display.
     *
     * @param $value
     *
     * @return string
     */
    public function getExpiresAtDisplayAttribute($value)
    {
        $timezone = config('app.timezone');
        $dateFormat = 'Y-m-d';
        if (auth()->check()) {
            $timezone = auth()->user()->timezone ?? $timezone;
            $dateFormat = auth()->user()->date_format ?? $dateFormat;
        }

        return $this->expires_at ? Timezone::convertFromUTC($this->ends_at, $timezone, $dateFormat) : '--';
    }

    public function used()
    {
        $this->times_redeemed = $this->times_redeemed + 1;
        $this->save();

        $this->coupon->used();
    }
}