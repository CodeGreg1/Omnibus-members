<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8'
        ];
    }

    /**
     * Get the password reset fields.
     *
     * @return array
     */
    public function credentials()
    {
        return $this->only('email', 'password', 'password_confirmation', 'token');
    }
}
