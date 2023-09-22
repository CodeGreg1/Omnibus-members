<?php

namespace Modules\Affiliates\Models;

use App\Models\User;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Affiliates\States\CommissionStatus\CommissionStatusState;

class AffiliateCommission extends Model
{
    use HasFactory, HasStates;

    protected $fillable = [
        "affiliate_user_id",
        "reffered_id",
        "amount",
        "currency",
        "rate",
        "type",
        "status",
        "approved_at",
        "approve_on_end",
        "rejected_at",
        "rejected_reason"
    ];

    protected $casts = [
        'status' => CommissionStatusState::class,
        'approved_at' => 'datetime',
        'approve_on_end' => 'datetime',
        'rejected_at' => 'datetime'
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
}
