<?php

namespace Modules\Affiliates\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Affiliates\States\CommissionStatus\Pending;

class AffiliateUser extends Model
{
    use HasFactory, QueryCacheable;

    protected $fillable = [
        "user_id",
        "code",
        "approved",
        "active",
        "rejected_at",
        "rejected_reason",
        "allow_registration_commission",
        "allow_deposit_commission",
        "allow_subscription_commission",
        "total_clicks"
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function () {
            static::flushQueryCache();
        });

        static::updated(function () {
            static::flushQueryCache();
        });

        static::deleted(function () {
            static::flushQueryCache();
        });
    }

    /**
     * Get the user that owns the affiliate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all referrals associated with affiliate user
     */
    public function referrals()
    {
        return $this->hasMany(AffiliateReferral::class, 'affiliate_user_id');
    }

    /**
     * Get all referral levels associated with affiliate user
     */
    public function referralLevels()
    {
        return $this->hasMany(AffiliateReferralLevel::class, 'affiliate_user_id');
    }

    /**
     * Get all commissions associated with affiliate user
     */
    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class, 'affiliate_user_id');
    }

    public function addCommission($user, $type, $currency, $amount, $rate)
    {
        return $this->commissions()->create([
            'reffered_id' => $user->id,
            'amount' => $amount,
            'currency' => $currency,
            'rate' => $rate,
            'type' => $type,
            'status' => Pending::$name,
            'approve_on_end' => now()->addDays(intval(setting('commision_verification_days_count', 0)))
        ]);
    }

    public function hasWithdrawables()
    {
        return !!$this->commissions()->where('status', 'pending')
            ->where('approve_on_end', '<=', now())->count();
    }
}
