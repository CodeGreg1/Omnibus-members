<?php

namespace Modules\Auth\Http\Controllers\Api\V1;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Modules\Auth\Http\Requests\ApiVerifyEmailRequest;
use Modules\Base\Http\Controllers\Api\BaseController;

/**
 * @group Auth endpoints
 *
 * APIs for system authentications
 */ 
class VerificationController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('resend');
    }

    /**
     * Verify user email address
     * 
     * It will resend the email verification notification
     * 
     * @authenticated
     * 
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "A fresh verification link has been sent to your email address."
     * }
     * @response status=400 scenario="Error" {
     *      "success": false,
     *      "message": "Email is already verified."
     * }
     * @response status=404 scenario="Not Found" {
     *      "success": false,
     *      "message": "Resource Not Found"
     * }
     * @response status=401 scenario="Not Found" {
     *      "message": "Unauthenticated."
     * }
     * 
     * 
     * @param ApiVerifyEmailRequest $request
     * @return JsonResponse
     */
    public function verify(ApiVerifyEmailRequest $request)
    {
        if (!setting('registration_email_confirmation')) {
            return $this->errorNotFound();
        }

        $this->verifySignature($request);

        if($request->user()->hasVerifiedEmail()) {
            return $this->emailAlreadyVerifiedResponse();
        }

        if($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->successResponse(__('Account successfully verifed.'));
    }

    /**
     * Verify request signature.
     *
     * @param ApiVerifyEmailRequest $request
     * @throws AuthorizationException
     */
    private function verifySignature(ApiVerifyEmailRequest $request)
    {
        $request = Request::create(route('auth.verification.verify', $request->only('id', 'hash')), Request::METHOD_GET, $request->only('expires', 'signature'));

        if (!$request->hasValidSignature()) {
            throw new InvalidSignatureException;
        }

        if (!hash_equals((string) $request->id, (string) auth()->user()->getKey())) {
            throw new AuthorizationException;
        }

        if (!hash_equals((string) $request->hash, sha1(auth()->user()->getEmailForVerification()))) {
            throw new AuthorizationException;
        }
    }

    /**
     * Handle email already verified response
     * 
     * @return JsonResponse
     */
    protected function emailAlreadyVerifiedResponse()
    {
        return $this->errorResponse(__('Email is already verified.'));
    }

    /**
     * Resend the email verification
     * 
     * It will resend the email verification notification
     * 
     * @authenticated
     * 
     * @response status=200 scenario="Success" {
     *      "success": true,
     *      "message": "A fresh verification link has been sent to your email address."
     * }
     * @response status=400 scenario="Error" {
     *      "success": false,
     *      "message": "Email is already verified."
     * }
     * @response status=404 scenario="Not Found" {
     *      "success": false,
     *      "message": "Resource Not Found"
     * }
     * @response status=401 scenario="Not Found" {
     *      "message": "Unauthenticated."
     * }
     * 
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->emailAlreadyVerifiedResponse();
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->successResponse(__('A fresh verification link has been sent to your email address.'));
    }
}
