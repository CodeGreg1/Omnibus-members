<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Base\Support\TimezoneKey;
use Modules\Base\Support\TimezoneValue;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'language' => 'required',
            'timezone' => 'required',
            'date_format' => 'required',
            'time_format' =>  'required',
            'currency' => 'required'
        ];

        if (request('company_name')) {
            $rules['company_name'] = 'required';
            $rules['company_number'] = 'required|integer';
            $rules['tax_number'] = 'required|integer';
            $rules['phone'] = 'required';
        }

        if (request('country')) {
            $rules['country'] = 'required';
            $rules['address'] = 'required';
            $rules['city'] = 'required';
            $rules['state'] = 'required';
            $rules['zip_code'] = 'required';
        }

        return $rules;
    }

    /**
     * Get user data
     * 
     * @return array
     */
    public function userData() 
    {
        $request = request()->only(
            'first_name', 
            'last_name', 
            'timezone', 
            'date_format',
            'time_format',
            'language',
            'currency'
        );

        $request['timezone'] = (new TimezoneValue)->get(
            request('timezone')
        );

        $request['timezone_display'] = (new TimezoneKey)->get(
            request('timezone')
        );

        if(request('language')) {
            $request['locale'] = $request['language'];
        }

        if (request('country')) {
            $request = array_merge($request, [
                'country_id' => request('country')
            ]);
        }

        return $request;
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

        $data = $this->transformer($data, [
            'address' => 'address_1',
            'apartment' => 'address_2',
            'country' => 'country_id'
        ]);

        return array_merge($data, [
            'user_id' => request()->route('id')
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
        return Gate::allows('admin.users.update');
    }
}
