<?php

namespace Modules\Carts\Traits;

use Modules\Carts\Models\PromoCode;
use Modules\AvailableCurrencies\Facades\Currency;

trait HasDiscount
{
    /**
     * Get the promo code of the checkout
     */
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    /**
     * Check if checkout has discount
     *
     * @param bool
     */
    public function hasDiscount()
    {
        return !!$this->promoCode;
    }

    /**
     * Get the checkout's discount price
     *
     * @param bool $formatted
     * @return string|float|int
     */
    public function getDiscountPrice($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();
        $discount = 0;
        if ($this->promoCode) {
            $coupon = $this->promoCode->coupon;

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