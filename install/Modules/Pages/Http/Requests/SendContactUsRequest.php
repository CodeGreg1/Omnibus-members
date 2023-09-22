<?php

namespace Modules\Pages\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class SendContactUsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ];

        if (setting('recaptcha_site_key') && setting('recaptcha_secret_key')) {
            $rules['g-recaptcha-response'] = 'recaptcha';
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
            'name.required' => __('The name is required.'),
            'email.required' => __('The email is required.'),
            'email.email' => __('The email is not valid.'),
            'subject.required' => __('The subject is required.'),
            'message.required' => __('The message is required.'),
            'g-recaptcha-response.required' => __('The captcha is required.'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return Gate::allows('contact-us.send');
        return true;
    }
}
