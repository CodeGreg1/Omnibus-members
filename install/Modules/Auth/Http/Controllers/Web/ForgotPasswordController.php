<?php

namespace Modules\Auth\Http\Controllers\Web;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Http\Requests\ForgotPasswordRequest;
use Modules\Base\Http\Controllers\Web\BaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return View
     */
    public function showLinkRequestForm()
    {
        return view('auth::forgot-password', [
            'pageTitle' => __('Forgot Password')
        ]);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  ForgotPasswordRequest  $request
     * 
     * @return RedirectResponse|JsonResponse
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * This is the response of password reset link after successfully processed.
     *
     * @param  Request  $request
     * @param  string  $response
     * 
     * @return JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $this->successResponse(trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  Request  $request
     * @param  string  $response
     * 
     * @return RedirectResponse
     * @throws ValidationException
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return $this->errorResponse(trans($response));
    }
}
