<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ];

        if(request()->has('terms') && setting('registration_tos')) {
            $rules = array_merge($rules, [
                'terms' => 'required'
            ]);
        }

        if(setting('api_registration_captcha')) {
            $rules = array_merge($rules, [
                'g-recaptcha-response' => 'recaptcha'
            ]);
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
        if(!setting('allow_registration')) {
            abort(400, __('New account registration is disabled.'));
        }

        return true;
    }
}
