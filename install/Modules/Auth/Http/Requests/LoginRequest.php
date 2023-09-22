<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required'
        ];
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

    /**
     * Get authorization credentials from the request
     *
     * @return array
     */
    public function getCredentials() 
    {
        // Support username or email to logging user
        // Check if the provided username is email so that we will change the key to "email"
        $username = $this->get('username');

        if($this->isEmail($username)):
            return [
                'email' => $username,
                'password' => $this->get('password')
            ];
        endif;

        return $this->only('username', 'password');
    }

    /**
     * Validate if username provided is email
     *
     * @param $username
     * @return bool
     * @throws BindingResolutionException
     */
    protected function isEmail($username)
    {
        $class = $this->container->make(ValidationFactory::class);

        return !$class->make(
            ['username' => $username],
            ['username' => 'email']
        )->fails();
    }
}
