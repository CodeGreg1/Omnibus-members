<?php

namespace Modules\Transactions\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Traits\WalletCurrencyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, WalletCurrencyScope;

    protected $fillable = [
        "trx",
        "user_id",
        "currency",
        "fixed_charge",
        "percent_charge_rate",
        "percent_charge",
        "amount",
        "charge",
        "added",
        "initial_balance",
        "description"
    ];

    public $appends = ['type'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->trx = strtoupper(uniqid('TRAN-'));
        });
    }

    /**
     * Get the parent transactionable model.
     */
    public function transactionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeAttribute()
    {
        return $this->getType();
    }

    public function getType()
    {
        $type = class_basename(get_class($this->transactionable));

        if ($type !== 'Wallet') {
            return $type;
        }

        if (Str::contains($this->description, 'Send')) {
            return 'Transfer';
        }

        if (Str::contains($this->description, 'Received')) {
            return 'Transfer';
        }

        if (Str::contains($this->description, 'Conversion')) {
            if (Str::contains($this->description, 'Subtracted')) {
                return 'Exchange';
            }
            if (Str::contains($this->description, 'Added')) {
                return 'Exchange';
            }
        }

        if (Str::contains($this->description, 'Deposited')) {
            return 'Deposit';
        }

        if (Str::contains($this->description, 'Withdraw')) {
            return 'Withdrawal';
        }

        if (Str::contains($this->description, 'Subscription')) {
            return 'Subscription';
        }

        if (Str::contains($this->description, 'commission')) {
            return 'Commission';
        }

        return $type;
    }

    public function getAmountDisplay($hasPrefix = false)
    {
        return ($hasPrefix ? ($this->added ? '+' : '-') : '') . currency_format($this->amount, $this->currency);
    }

    public function getChargeDisplay()
    {
        $operation = '+';
        if (in_array($this->type, ['Withdrawal', 'Exchange'])) {
            $operation = '-';
        }
        return $operation . currency_format($this->charge, $this->currency);
    }

    public function getTotal($formatted = true)
    {
        $total = $this->amount;
        if ($this->type === 'Transfer') {
            $total = $this->amount + $this->charge;
        }

        if ($this->type === 'Exchange') {
            $total = $this->amount - $this->charge;
        }

        if ($this->type === 'Withdrawal') {
            $total = $this->amount - $this->charge;
        }

        if ($this->type === 'Deposit') {
            $total = $this->amount + $this->charge;
        }

        if (!$formatted) {
            return $total;
        }

        return currency_format($total, $this->currency);
    }
}
