<?php

namespace Modules\Frontend\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBreadcrumbSectionSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'frontend_breadcrumb_background_style' => 'required|in:image,color'
        ];

        if ($this->get('frontend_breadcrumb_background_style') === 'color') {
            $rules['frontend_breadcrumb_bg_color'] = 'required';
        } else {
            $rules['frontend_breadcrumb_bg_image'] = 'required';
        }

        $rules['frontend_breadcrumb_text_color'] = 'required';
        $rules['frontend_breadcrumb_page_title_color'] = 'required';

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->get('frontend_breadcrumb_background_style') === 'color') {
            $frontend_breadcrumb_bg_color = $this->get('frontend_breadcrumb_bg_color');
            $frontend_breadcrumb_bg_image = '';
        } else {
            $frontend_breadcrumb_bg_color = '';
            $frontend_breadcrumb_bg_image = $this->get('frontend_breadcrumb_bg_image');
        }

        $this->merge([
            'frontend_breadcrumb_bg_image' => $frontend_breadcrumb_bg_image,
            'frontend_breadcrumb_bg_color' => $frontend_breadcrumb_bg_color
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'frontend_breadcrumb_background_style.required' => __('Background style is required.'),
            'frontend_breadcrumb_background_style.enum' => __('Background style is not valid.'),
            'frontend_breadcrumb_bg_color' => __('Background color is required.'),
            'frontend_breadcrumb_bg_image' => __('Background image is required.')
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
