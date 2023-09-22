<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'maintenance_secret_code' => 'required_with:maintenance_enabled'
        ];

        if ($this->get('maintenance_secret_code')) {
            $rules['maintenance_secret_code'] = ['regex:/^[0-9a-zA-Z_\-]*$/'];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'maintenance_secret_code.required_with' => __('Please provide secret code if you will enable maintenance mode.'),
            'maintenance_secret_code.regex' => __('The secret code is not valid.')
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.maintenance.settings.update');
    }
}
