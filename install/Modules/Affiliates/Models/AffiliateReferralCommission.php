<?php

namespace Modules\Affiliates\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateReferralCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        "affiliate_referral_level_id",
        "amount",
        "currency"
    ];
}
