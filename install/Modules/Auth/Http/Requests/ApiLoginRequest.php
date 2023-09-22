<?php

namespace Modules\Auth\Http\Requests;

use Modules\Auth\Http\Requests\LoginRequest;

class ApiLoginRequest extends LoginRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'device_name' => 'required',
        ]);
    }

    /**
     * Get authorization credentials from the request
     *
     * @return void
     */
    public function getCredentials()
    {
        $creds = parent::getCredentials();

        unset($creds['password']);

        return $creds;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
