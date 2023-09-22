<?php

namespace Modules\Subscriptions\Services\Concerns;

trait AllowsCoupons
{
    /**
     * The promotion code being applied.
     *
     * @var Modules\Subscriptions\Models\PromoCode|null
     */
    protected $promotionCode;

    protected $couponAmount = 0;

    /**
     * The promotion code to apply.
     *
     * @param  Modules\Subscriptions\Models\PromoCode  $promotionCode
     * @return $this
     */
    public function withPromotionCode($promotionCode)
    {
        $this->promotionCode = $promotionCode;

        $this->applyDiscount();

        return $this;
    }

    protected function applyDiscount()
    {
        if ($this->promotionCode) {
            $price = $this->price->price;
            if ($this->promotionCode->coupon->amount_type === 'percentage') {
                $discount = (($this->promotionCode->coupon->amount / 100) * $price);
                $price = $price - $discount;
                $this->couponAmount = $discount;
            } else {
                $couponAmount = convert_to_cents($this->promotionCode->coupon->amount);
                $price = $price - $couponAmount;
                $this->couponAmount = $couponAmount;
            }

            $this->price->price = (int) $price;
        }
    }

    protected function getPromoCodeId()
    {
        if ($this->promotionCode) {
            return $this->promotionCode->id;
        }
        return null;
    }

    protected function getCouponPayload()
    {
        if ($this->promotionCode) {
            return [
                'promo_code_id' => $this->promotionCode->id,
                'value' => $this->promotionCode->coupon->amount,
                'type' => $this->promotionCode->coupon->amount_type,
                'amount' => $this->couponAmount
            ];
        }
        return null;
    }
}