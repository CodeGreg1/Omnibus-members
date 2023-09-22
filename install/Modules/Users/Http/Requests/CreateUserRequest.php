<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['email'] = 'required|email:rfc,dns|unique:users,email';
        $rules['first_name'] = 'required';
        $rules['last_name'] = 'required';
        $rules['role'] = 'required';

        if (request('password')) {
            $rules['password'] = 'required|min:8';
            $rules['confirm_password'] = 'required|same:password';
        }

        if (request('company_name')) {
            $rules['company_name'] = 'required';
            $rules['company_number'] = 'required|integer';
            $rules['tax_number'] = 'required|integer';
            $rules['phone'] = 'required';
            $rules['country'] = 'required';
            $rules['address'] = 'required';
            $rules['city'] = 'required';
            $rules['state'] = 'required';
            $rules['zip_code'] = 'required';
        }

        return $rules;
    }

    public function userData() 
    {
        $data = request()->only('email', 'first_name', 'last_name');

        if (request('password')) {
            $data['password'] = request('password');
        } else {
            // $data['password'] = 
        }



        return request()->only('email', 'first_name', 'last_name', 'password');
    }

    /**
     * Process company request data that suitable with table field names
     * and append user_id
     * 
     * @return array
     */
    public function companyData() 
    {
        $data = request()->only('company_name', 'company_number', 'tax_number', 'phone');

        return $this->transformer($data, [
            'company_name' => 'name',
            'company_number' => 'number'
        ]);
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

        return $this->transformer($data, [
            'address' => 'address_1',
            'apartment' => 'address_2',
            'country' => 'country_id'
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
        return Gate::allows('admin.users.store');
    }
}
