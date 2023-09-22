<?php

namespace Modules\Wallet\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Traits\WalletCurrencyScope;
use Modules\Transactions\Traits\Transactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory, Transactionable, WalletCurrencyScope;

    protected $fillable = [
        "user_id",
        "currency",
        "balance",
        "commision"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
