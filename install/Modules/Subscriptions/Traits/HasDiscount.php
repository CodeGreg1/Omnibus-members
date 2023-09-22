<?php

namespace Modules\Subscriptions\Traits;

use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Subscriptions\Models\SubscriptionCoupon;

trait HasDiscount
{
    /**
     * Get the promo code that owns the subscription
     */
    public function discount()
    {
        return $this->belongsTo(SubscriptionCoupon::class, 'id', 'subscription_id');
    }

    /**
     * check if subscription has any discounts applied
     *
     * @return bool
     */
    public function hasDiscount()
    {
        return !!$this->discount()->count();
    }

    /**
     * Get the checkout's discount price
     *
     * @param bool $formatted
     * @return string|float|int
     */
    public function getTotalDiscount($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();
        $discount = 0;
        if ($this->discount) {
            $coupon = $this->discount->promoCode->coupon;

            if ($coupon->amount_type === 'fixed') {
                $discount = $coupon->getFixedPrice(false, $currency);
            } else {
                $subtotal = $this->getSubtotal(false, $currency);
                $discount = $subtotal * ($coupon->amount / 100);
            }

            $discount = number_format($discount, 2, '.', '');
        }

        if (!$formatted) {
            return $discount;
        }

        return currency_format(
            $discount,
            $currency
        );
    }
}