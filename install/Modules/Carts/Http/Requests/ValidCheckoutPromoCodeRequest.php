<?php

namespace Modules\Carts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidCheckoutPromoCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'checkout_coupon' => 'required|exists:promo_codes,code'
        ];
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