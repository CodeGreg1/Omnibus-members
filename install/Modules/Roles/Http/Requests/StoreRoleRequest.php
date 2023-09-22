<?php

namespace Modules\Roles\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
            'permissions' => 'required',
            'type' => 'required'
        ];
    }
    
    /**
     * Get the validation custom messages
     * 
     * @return array
     */
    public function messages() 
    {
        return [
            'permissions.required' => __('Please select at least 1 permission.')
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.roles.store');
    }
}
