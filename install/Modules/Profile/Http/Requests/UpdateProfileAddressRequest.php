<?php

namespace Modules\Profile\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'country' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required'
        ];
    }

    /**
     * Process address request data that suitable with table field names
     * and append user_id
     * 
     * @return array
     */
    public function addressData() 
    {
        $data = request()->only('country', 'address', 'apartment', 'city', 'state', 'zip_code');

        $data = $this->transformer($data, [
            'address' => 'address_1',
            'apartment' => 'address_2',
            'country' => 'country_id'
        ]);

        return array_merge($data, [
            'user_id' => auth()->id()
        ]);
    }

    /**
     * Field name key transformer
     * 
     * @param array $data
     * @param array @transfer
     * 
     * @return array
     */
    protected function transformer($data, $transformer) 
    {
        foreach ( $transformer as $old => $new ) {
            $data[$new] = $data[$old];
            unset( $data[$old] );
        }

        return $data;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('profile.address-update');
    }
}
