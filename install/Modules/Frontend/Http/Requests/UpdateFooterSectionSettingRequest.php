<?php

namespace Modules\Frontend\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFooterSectionSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'frontend_footer_about_us' => 'required',
            'frontend_footer_copyright_text' => 'required'
        ];

        if ($this->has('frontend_footer_menu1_title')) {
            $rules['frontend_footer_menu1'] = 'required|exists:menus,id';
        }

        if ($this->has('frontend_footer_menu1')) {
            $rules['frontend_footer_menu1_title'] = 'required';
        }

        if ($this->has('frontend_footer_menu2_title')) {
            $rules['frontend_footer_menu2'] = 'required|exists:menus,id';
        }

        if ($this->has('frontend_footer_menu2')) {
            $rules['frontend_footer_menu2_title'] = 'required';
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
            'frontend_footer_logo.required' => __('The footer logo is required.'),
            'frontend_footer_menu1_title.required' => __('The menu 1 title is required.'),
            'frontend_footer_menu1.required' => __('The menu 1 is required.'),
            'frontend_footer_menu1.exists' => __('The menu 1 is not found.'),
            'frontend_footer_menu2_title.required' => __('The menu 2 title is required.'),
            'frontend_footer_menu2.required' => __('The menu 2 is required.'),
            'frontend_footer_menu2.exists' => __('The menu 2 is not found.'),
            'frontend_footer_about_us.required' => __('The footer about us is required.'),
            'frontend_footer_copyright_text.required' => __('The footer copyright text is required.'),
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
