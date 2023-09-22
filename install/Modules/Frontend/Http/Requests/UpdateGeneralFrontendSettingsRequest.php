<?php

namespace Modules\Frontend\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralFrontendSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'frontend_page_title' => 'required|max:70',
            'frontend_page_description' => 'required|max:320',
            'frontend_social_sharing_image' => 'required',
            'frontend_primary_menu' => 'required|exists:menus,id'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'frontend_page_title.required' => __('The page title is required.'),
            'frontend_page_title.max' => __('The page title must not greater than 70 characters.'),
            'frontend_page_description.required' => __('The page description is required.'),
            'frontend_page_description.max' => __('The page description must not greater than 320 characters.'),
            'frontend_social_sharing_image.required' => __('The social sharing image is required.'),
            'frontend_primary_menu.required' => __('The primary menu is required.'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.frontends.settings.update');
    }
}
