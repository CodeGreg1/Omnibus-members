<?php

namespace Modules\Carts\Http\Requests;

use Modules\Carts\Models\Coupon;
use Modules\Carts\Models\PromoCode;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StorePromoCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'coupon_id' => 'required|exists:coupons,id',
            'code' => 'required'
        ];

        $coupon = Coupon::findOrFail($this->get('coupon_id'));
        $timesRedeemed = $coupon->promoCodes()->get()->sum(function ($promoCode) {
            return $promoCode->subscriptions()->count();
        }, 0);
        if ($coupon && $this->get('max_redemptions') && $coupon->redeem_limit_count) {
            $limit = $coupon->redeem_limit_count - $timesRedeemed;
            $rules['max_redemptions'] = ['numeric', 'max:' . $limit];
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'coupon_id' => $this->coupon,
            'active' => 1,
            'expires_at' => null,
            'max_redemptions' => intval($this->max_redemptions)
        ]);
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->isCodeUsed()) {
                $validator->errors()->add('code', __('Code already in use'));
            }

            if (!$this->canAddCode()) {
                $validator->errors()->add('code', __('Cannot add anymore code. Coupon max redemptionn limit reached.'));
            }
        });
    }

    /**
     * Check if coupon is unique
     *
     * @return bool
     */
    protected function isCodeUsed()
    {
        return !!PromoCode::where('code', $this->get('code'))->count();
    }

    /**
     * Check if coupon is not yet reach the max limit
     *
     * @return bool
     */
    protected function canAddCode()
    {
        $coupon = Coupon::findOrFail($this->get('coupon_id'));
        if (!$coupon->redeem_limit_count) {
            return true;
        }

        $timesRedeemed = $coupon->promoCodes()->get()->sum(function ($promoCode) {
            return $promoCode->subscriptions()->count();
        }, 0);

        return $coupon->redeem_limit_count > $timesRedeemed;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => __('Please provide a unique code'),
            'code.unique' => __('Try another code. Code already exist'),
            'max_redemptions.max' => __('The :attribute must not be greater than :max.')
        ];
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.coupons.promo-codes.store');
    }
}
