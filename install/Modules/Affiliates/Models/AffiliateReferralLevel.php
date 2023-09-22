<?php

namespace Modules\Affiliates\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateReferralLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        "affiliate_user_id",
        "reffered_id",
        "level"
    ];

    /**
     * Get the affiliate that owns the referral.
     */
    public function affiliate()
    {
        return $this->belongsTo(AffiliateUser::class, 'affiliate_user_id');
    }

    /**
     * Get the user that owns the referral.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'reffered_id');
    }

    /**
     * Get all commissions associated with affiliate referral level
     */
    public function commissions()
    {
        return $this->hasMany(
            AffiliateCommission::class,
            'affiliate_user_id',
            'affiliate_user_id'
        );
    }
}
