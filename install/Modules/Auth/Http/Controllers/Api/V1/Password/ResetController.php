<?php

namespace Modules\Auth\Http\Controllers\Api\V1\Password;

use Mail;
use Modules\Auth\Emails\ResetPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Modules\Users\Repositories\UserRepository;
use Illuminate\Contracts\Auth\CanResetPassword;
use Modules\Auth\Http\Requests\PasswordResetRequest;
use Modules\Base\Http\Controllers\Api\BaseController;

/**
 * @group Auth endpoints
 *
 * APIs for system authentications
 */
class ResetController extends BaseController
{

    /**
     * Reset password
     * 
     * @bodyParam token string required The token sent when request password reset
     * @bodyParam email string required The user registered email and must be a valid email address.
     * @bodyParam password string required The new password
     * @bodyParam password_confirmation string required Confirm the new password
     * 
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "Your password has been reset!"
     * }
     * @response status=422 scenario="Error Validations" {
     *       "message": "The token field is required. (and 1 more error)",
     *      "errors": {
                "token": [
                    "The token field is required."
                ],
     *          "email": [
     *              "The email field is required.",
     *              "The email must be a valid email address."
     *          ],
     *          "password": [
     *              "The password field is required.",
     *              "The password confirmation does not match.",
     *              "The password must be at least 8 characters."
     *          ]
     *      }
     * }
     * @response status=400 scenario="Error" {
     *      "success": false,
     *      "message": "This password reset token is invalid"
     * }
     * 
     * 
     * @param PasswordRemindRequest $request
     * 
     * @return JsonResponse
     */
    public function index(PasswordResetRequest $request)
    {
        $response = $this->handleResponse($request);

        if($response == Password::PASSWORD_RESET) {
            return $this->successResponse(
                __('Your password has been reset!')
            );
        }

        return $this->errorResponse(
            __('This password reset token is invalid')
        );
    }

    /**
     * Handle response
     * 
     * @return Request $request
     * 
     * @return string
     */
    protected function handleResponse($request) 
    {
        return Password::reset($request->credentials(), function ($user, $password) {
            $this->handleResetPassword($user, $password);
        });
    }

    /**
     * Reset the given user's password.
     *
     * @param  CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function handleResetPassword($user, $password)
    {
        $user->password = $password;
        $user->save();

        event(new PasswordReset($user));
    }
}
