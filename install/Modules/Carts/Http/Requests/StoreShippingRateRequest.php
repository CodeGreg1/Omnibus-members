<?php

namespace Modules\Carts\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Carts\Models\ShippingRate;
use Illuminate\Foundation\Http\FormRequest;

class StoreShippingRateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'price' => 'required|numeric',
            'currency' => 'required'
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
            'active' => $this->has('active') ? 1 : 0
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
            if ($this->isTitleUsed()) {
                $validator->errors()->add('title', __('Title already in use'));
            }
        });
    }

    /**
     * Check if shipping rate is unique
     *
     * @return bool
     */
    protected function isTitleUsed()
    {
        return !!ShippingRate::where('title', $this->get('title'))->count();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.shipping-rates.store');
    }
}