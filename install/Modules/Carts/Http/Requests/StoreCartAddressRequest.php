<?php

namespace Modules\Carts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'country_id' => 'exists:countries,id',
            'name' => 'required',
            'description' => 'nullable',
            'address_1' => 'required',
            'address_2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required'
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