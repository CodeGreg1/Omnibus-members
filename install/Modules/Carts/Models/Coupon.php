<?php

namespace Modules\Carts\Models;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Cashier\Traits\CashierModeScope;
use Modules\AvailableCurrencies\Facades\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory, CashierModeScope;

    protected $fillable = [
        "name",
        "currency",
        "amount",
        "amount_type",
        "billing_duration",
        "redeem_date_end",
        "redeem_limit_count",
        "times_redeemed",
        "live"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'float'
    ];

    /**
     * Append modified field values
     */
    protected $appends = [
        'redeem_date_at'
    ];

    /**
     * Get formatted redeem date end.
     *
     * @param $value
     *
     * @return string
     */
    public function getRedeemDateAtAttribute($value)
    {
        return Carbon::parse($this->redeem_date_end);
    }


    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }

    /**
     * The users that belong to the coupon.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function used()
    {
        $this->times_redeemed = $this->times_redeemed + 1;
        $this->save();
    }

    /**
     * Check if coupon has subscriptions
     *
     * @return bool
     */
    public function hasSubscriptions()
    {
        return !!$this->promoCodes()->get()
            ->filter(function ($promoCode) {
                return $promoCode->hasSubscriptions();
            })->count();
    }

    /**
     * Get actual fixed amount by user currency
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float|int
     */
    public function getFixedPrice($formatted = true, $currency = null)
    {
        return currency(
            $this->amount,
            $this->currency,
            $currency ?? Currency::getUserCurrency(),
            $formatted
        );
    }

    /**
     * Get coupons description
     *
     * @return string
     */
    public function getDescription()
    {
        $str = '';
        if ($this->amount_type === 'fixed') {
            $str = currency(
                $this->amount,
                $this->currency,
                Currency::getUserCurrency()
            );
        } else {
            $str = real_number($this->amount) . '%';
        }

        return $str . ' off';
    }
}