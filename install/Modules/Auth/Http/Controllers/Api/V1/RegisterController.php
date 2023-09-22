<?php

namespace Modules\Auth\Http\Controllers\Api\V1;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Modules\Users\Repositories\UserRepository;
use Modules\Auth\Http\Requests\ApiRegisterRequest;
use Modules\Base\Http\Controllers\Api\BaseController;

/**
 * @group Auth endpoints
 *
 * APIs for system authentications
 */ 
class RegisterController extends BaseController
{   
    /**
     * Registration controller instance.
     * 
     * @param UserRepository $users
     */
    protected $users;

    public function __construct(UserRepository $users) 
    {
        $this->users = $users;
    }

    /**
     * User registration
     * 
     * Register new user with this endpoint. Once registered successfully
     * you need to check your mail box if requires_email_confirmation
     * is set to true.
     * 
     * @bodyParam email string required The user email address to be registered, must be an email, and unique.
     * @bodyParam username string required The username of user must be unique.
     * @bodyParam password string required The user password with at least 8 characters.
     * @bodyParam password_confirmation string required Confirm the user password.
     * 
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "Account created successfully.",
     *      "data": {
     *          "requires_email_confirmation": true
     *      }
     * }
     * @response status=422 scenario="Error Validations" {
     *       "message": "The email field is required.",
     *      "errors": {
     *          "email": [
     *              "The email field is required.",
     *              "The email has already been taken."
     *          ],
     *          "username": [
     *              "The username field is required.",
     *              "The username has already been taken."
     *          ],
     *          "password": [
     *              "The password field is required.",
     *              "The password must be at least 8 characters."
     *           ],
     *          "password_confirmation": [
     *              "The password confirmation and password must match.",
     *           ],
     *      }
     * }
     * 
     * 
     * @param ApiRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(ApiRegisterRequest $request)
    {
        $user = $this->users->create(array_merge(
            $request->validated(),
            [
                'email_verified_at' => (setting('registration_email_confirmation') ? null : now())
            ]
        ));

        // Assign registered with default role
        if(setting('registration_role')) {
            $user->assignRole(setting('registration_role'));
        }

        event(new Registered($user));
   
        return $this->successResponse(
            __('Account created successfully.'), [
                'requires_email_confirmation' => !!setting('registration_email_confirmation')
            ],
            Response::HTTP_CREATED
        );
    }
}
