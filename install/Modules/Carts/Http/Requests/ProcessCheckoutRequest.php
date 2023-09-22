<?php

namespace Modules\Carts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCheckoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if ($this->has('shipping_address_id')) {
            $rules['shipping_address_id'] = ['exists:addresses,id'];
        } else if ($this->has('shipping_address')) {
            $rules['shipping_address'] = ['required'];
            $rules['shipping_address.name'] = ['required'];
            $rules['shipping_address.address_1'] = ['required'];
            $rules['shipping_address.state'] = ['required'];
            $rules['shipping_address.city'] = ['required'];
            $rules['shipping_address.country_id'] = ['required', 'exists:countries,id'];
            $rules['shipping_address.zip_code'] = ['required'];
        } else {
        }

        if ($this->has('billing_address_id')) {
            $rules['billing_address_id'] = ['exists:addresses,id'];
        } else if ($this->has('billing_address')) {
            $rules['billing_address'] = ['required'];
            $rules['billing_address.name'] = ['required'];
            $rules['billing_address.address_1'] = ['required'];
            $rules['billing_address.state'] = ['required'];
            $rules['billing_address.city'] = ['required'];
            $rules['billing_address.country_id'] = ['required', 'exists:countries,id'];
            $rules['billing_address.zip_code'] = ['required'];
        } else {
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}