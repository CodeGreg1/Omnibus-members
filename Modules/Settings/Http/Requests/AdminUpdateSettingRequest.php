<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $defaultProfilePhotoValidation = '';
        if(is_null(request('default_profile_photo')) && is_null(setting('default_profile_photo'))) {
            $defaultProfilePhotoValidation = 'required';
        }

        $coloredLogoValidation = '';
        if(is_null(request('colored_logo')) && is_null(setting('colored_logo'))) {
            $coloredLogoValidation = 'required';
        }

        $whiteLogoValidation = '';
        if(is_null(request('white_logo')) && is_null(setting('white_logo'))) {
            $whiteLogoValidation = 'required';
        }

        $faviconValidation = '';
        if(is_null(request('favicon')) && is_null(setting('favicon'))) {
            $faviconValidation = 'required';
        }

        $rules = [
            'default_profile_photo' => $defaultProfilePhotoValidation,
            'default_profile_photo.*' => 'image|mimes:jpeg,png,jpg,gif,ico|max:2048|dimensions:max_width=500,max_height=500',
            'colored_logo' => $coloredLogoValidation,
            'colored_logo.*' => 'image|mimes:jpeg,png,jpg,gif,ico|max:2048|dimensions:max_width=240,max_height=70',
            'white_logo' => $whiteLogoValidation,
            'white_logo.*' => 'image|mimes:jpeg,png,jpg,gif,ico|max:2048|dimensions:max_width=240,max_height=70',
            'favicon' => $faviconValidation,
            'favicon.*' => 'max:2048|dimensions:max_width=40,max_height=40',
        ];

        if ($this->has('storage_driver')) {
            if ($this->get('storage_driver') === 's3') {
                $rules['aws_access_key_id'] = 'required';
                $rules['aws_secret_access_key'] = 'required';
                $rules['aws_default_region'] = 'required';
                $rules['aws_bucket'] = 'required';
            }
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
        return Gate::allows('admin.settings.update');
    }
}
