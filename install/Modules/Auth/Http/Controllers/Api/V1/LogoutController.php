<?php

namespace Modules\Auth\Http\Controllers\Api\V1;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Modules\Base\Http\Controllers\Api\BaseController;

/**
 * @group Auth endpoints
 *
 * APIs for system authentications
 */ 
class LogoutController extends BaseController
{
    /**
     * User logout
     * 
     * When you logout authenticated user it will remove the token you used.
     * And cannot be use again. So you need to login again if you want to access
     * some modules.
     * 
     * @authenticated
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "Successfully logout."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *       "message": "Unauthenticated."
     * }
     * 
     * 
     * @param ApiLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $guard = new Auth;

        auth()->user()->currentAccessToken()->delete();

        event(new Logout($guard, auth()->user()));

        return $this->successResponse(__('Successfully logout.'));
    }
}
