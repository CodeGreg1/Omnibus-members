<?php

namespace Modules\Frontend\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNavbarSectionSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'frontend_navbar_bg_color' => 'required',
            'frontend_navbar_menu_text_color' => 'required',
            'frontend_navbar_menu_text_hover_color' => 'required',
            'frontend_navbar_menu_text_active_color' => 'required'
        ];

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
            'frontend_navbar_bg_color.required' => __('Background color is required.'),
            'frontend_navbar_menu_text_color.required' => __('Menu text is required.'),
            'frontend_navbar_menu_text_hover_color.required' => __('Menu text hover is required.'),
            'frontend_navbar_menu_text_active_color.required' => __('Menu text active is required.')
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
