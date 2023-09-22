<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['role'] = 'required';

        if (request('email')) {
            $rules['email'] = 'required|email:rfc,dns|unique:users,email,'.request()->route('id');
        }

        if (request('username') != '') {
            $rules['username'] = 'unique:users,username,'.request()->route('id');
        }

        if (request('password')) {
            $rules['password'] = 'required|min:8';
            $rules['confirm_password'] = 'required|same:password';
        }

        return $rules;
    }

    /**
     * Get user settings data
     */
    public function data() 
    {   
        $data['status'] = request('status');
        $data['username'] = request('username');

        if (request('email')) {
            $data['email'] = request('email');
        }

        if (request('password')) {
            $data['password'] = request('password');
        }

        return $data;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.users.update-user-settings');
    }
}
