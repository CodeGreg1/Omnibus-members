<?php

namespace Modules\Carts\Http\Requests;

use Modules\Carts\Models\PromoCode;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'active' => $this->has('active') ? 1 : 0,
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
                $validator->errors()->add('code', __('Try another code. Code already exist'));
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
        return !!PromoCode::where('code', $this->get('code'))
            ->where('id', '<>', $this->id)
            ->count();
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => __('Please provide a unique code')
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.coupons.promo-codes.update');
    }
}
