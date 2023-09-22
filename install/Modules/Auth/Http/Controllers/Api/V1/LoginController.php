<?php

namespace Modules\Auth\Http\Controllers\Api\V1;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Auth\Events\LoggedIn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\LoginRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Modules\Auth\Http\Requests\ApiLoginRequest;
use Modules\Base\Http\Controllers\Api\BaseController;

/**
 * @group Auth endpoints
 *
 * APIs for system authentications
 */ 
class LoginController extends BaseController
{
    /**
     * User login
     * 
     * In user loing when it will successfully login it will
     * generate token that you can use to access another modules.
     * Just store it in a safe storage so that you can call it
     * everytime you need it.
     * 
     * @bodyParam username string required The user registered username|email (if email must be a valid email address).
     * @bodyParam password string required The user password.
     * @bodyParam device_name string required The user device name currently user like iPhone or Android
     * 
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "Successfully login.",
     *      "data": {
     *          "token": "6|kdM2mYn6Ldk0qHc0jYRYeTBqIxKAq7bIyzrGZJyd"
     *      }
     * }
     * @response status=422 scenario="Error Validations" {
     *       "message": "The email field is required.",
     *      "errors": {
     *          "username": [
     *              "The username field is required.",
     *              "These credentials do not match our records."
     *          ],
     *          "password": [
     *              "The password field is required."
     *           ],
     *          "device_name": [
     *               "The device name field is required."
     *          ]
     *      }
     * }
     * 
     * 
     * @param ApiLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(ApiLoginRequest $request)
    {
        $user = $this->getUserByCredentials($request);

        if ($user->isBanned()) {
            return $this->errorUnauthorized(__('Your account is banned by administrators.'));
        }

        Auth::setUser($user);

        event(new LoggedIn($user));

        return $this->successResponse(__('Successfully login.'), [
            'token' => $user->createToken($request->device_name)->plainTextToken
        ]);
    }

    /**
     * Get the user instance with the API request.
     *
     * @param ApiLoginRequest $request
     * 
     * @return mixed
     * @throws ValidationException
     */
    protected function getUserByCredentials(ApiLoginRequest $request)
    {
        $user = User::where($request->getCredentials())->first();

        // throw exception if user is not found
        $this->throwErrorIfNotFound($user, $request);

        return $user;
    }

    /**
     * Handle throwing error if user not found
     * 
     * @return void
     * @throws ValidationException
     */
    protected function throwErrorIfNotFound($user, $request) 
    {
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => [__('auth.failed')],
            ]);
        }
    }
}
