<?php

namespace Modules\Profile\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileCompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name' => 'required',
            'company_number' => 'required|integer',
            'tax_number' => 'required|integer'
        ];
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
        return Gate::allows('profile.company-update');
    }
}
