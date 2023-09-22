<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Carts\Models\PromoCode;

class SubscriptionCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        "subscription_id",
        "promo_code_id",
        "value",
        "type",
        "amount"
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['promoCode'];

    /**
     * Append modified field values
     */
    protected $appends = [
        'amount_display',
    ];

    /**
     * Get the promo code that owns the subscription coupon
     */
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    /**
     * Get the subscription that of the subscription coupon
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * Get formatted price.
     *
     * @param $value
     *
     * @return string
     */
    public function getAmountDisplayAttribute($value)
    {
        return $this->amount * 0.01;
    }

    /**
     * Get the readable name for the Coupon.
     *
     * @return string
     */
    public function name()
    {
        return $this->promoCode->code ?: $this->promoCode->id;
    }

    /**
     * Determine if the coupon is a percentage.
     *
     * @return bool
     */
    public function isPercentage()
    {
        return $this->type === 'percentage';
    }

    /**
     * Get the discount percentage for the invoice.
     *
     * @return float|null
     */
    public function percentOff()
    {
        return $this->coupon->value;
    }

    /**
     * Get the amount off for the coupon.
     *
     * @return string|null
     */
    public function amountOff()
    {
        if (!is_null($this->promoCode->coupon->amount)) {
            return $this->formatAmount($this->rawAmountOff());
        }
    }

    /**
     * Get the raw amount off for the coupon.
     *
     * @return int|null
     */
    public function rawAmountOff()
    {
        return $this->promoCode->coupon->amount;
    }

    /**
     * Format the given amount into a displayable currency.
     *
     * @param  int  $amount
     * @return string
     */
    protected function formatAmount($amount)
    {
        return currency_format($amount, $this->subscription->currency->code);
    }
}