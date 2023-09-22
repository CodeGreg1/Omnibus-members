<?php

namespace Modules\Carts\Http\Requests;

use Illuminate\Support\Carbon;
use Modules\Carts\Models\Coupon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'currency' => 'required_if:type,fixed'
        ];

        if ($this->get('type') === 'percentage') {
            $rules['percentage_value'] = ['required_if:type,percentage', 'numeric', 'min:0.1', 'max:99.9'];
        }

        if ($this->get('type') === 'fixed') {
            $rules['fixed_amount'] = ['required_if:type,percentage', 'numeric', 'min:0.1'];
        }

        if ($this->has('withRedeemLimit')) {
            $rules['redeem_limit_count'] = ['required', 'integer', 'min:1'];
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
            'currency' => $this->request->get('type') === 'percentage' ? null : $this->request->get('currency'),
            'amount' => $this->request->get('type') === 'percentage' ? $this->request->get('percentage_value') : $this->request->get('fixed_amount'),
            'amount_type' => $this->request->get('type'),
            'billing_duration' => 0,
            'redeem_date_end' => $this->request->has('withLimitDate') ? Carbon::create($this->request->get('redeem_until'))->timestamp : null,
            'redeem_limit_count' => $this->request->has('withRedeemLimit') ? $this->request->get('redeem_limit_count') : 0
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
            if ($this->isNameUsed()) {
                $validator->errors()->add('name', __('Name already in use'));
            }
        });
    }

    /**
     * Check if coupon is unique
     *
     * @return bool
     */
    protected function isNameUsed()
    {
        return !!Coupon::where('name', $this->get('name'))->count();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.coupons.store');
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'redeem_limit_count.required' => __('Please provide total number of times this coupon can be redeemed'),
            'percentage_value.required_if' => __('Please provide percentage value'),
            'fixed_amount.required_if' => __('Please provide fixed amount')
        ];
    }
}
