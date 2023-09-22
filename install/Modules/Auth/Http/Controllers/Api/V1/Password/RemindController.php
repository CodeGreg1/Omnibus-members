<?php

namespace Modules\Auth\Http\Controllers\Api\V1\Password;

use Mail;
use Illuminate\Http\JsonResponse;
use Modules\Auth\Emails\ResetPassword;
use Illuminate\Support\Facades\Password;
use Modules\Users\Repositories\UserRepository;
use Modules\Auth\Http\Requests\PasswordRemindRequest;
use Modules\Base\Http\Controllers\Api\BaseController;

/**
 * @group Auth endpoints
 *
 * APIs for system authentications
 */ 
class RemindController extends BaseController
{

    /**
     * User reset password request
     * 
     * @bodyParam email string required The user registered email and must be a valid email address.
     * 
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "We have emailed your password reset link!"
     * }
     * @response status=422 scenario="Error Validations" {
     *       "message": "The email field is required.",
     *      "errors": {
     *          "email": [
     *              "The email field is required."
     *          ]
     *      }
     * }
     * 
     * 
     * @param PasswordRemindRequest $request
     * @param UserRepository $users
     * 
     * @return JsonResponse
     */
    public function index(PasswordRemindRequest $request, UserRepository $users)
    {
        $user = $users->findByEmail($request->email);

        $this->sendResetPassword($user, $request);

        return $this->successResponse(__('We have emailed your password reset link!'));
    }

    /**
     * Handle sending reset password
     * 
     * @param UsersRepository $user
     * @param Request $request
     * 
     * @return void
     */
    protected function sendResetPassword($user, $request) 
    {
        $token = $this->getToken($user);

        Mail::to($user)->send(new ResetPassword($token, $request->email));
    }

    /**
     * Handle getting user token
     * 
     * @param UserRepository $user
     * 
     * @return string
     */
    protected function getToken($user) 
    {
        return Password::getRepository()->create($user);
    }
}
