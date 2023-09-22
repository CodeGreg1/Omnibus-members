<?php

namespace Modules\Auth\Services\TwoFactor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait TwoFactorTrait
{
	/**
     * Handle redirect to two factor page for extra security
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function logoutAndRedirectToTwoFactor(Request $request, $user, $isRedirected = false) 
    {
        Auth::logout();

        if(setting('remember_me') && $request->get('remember')):
            $request->session()->put('auth.remember_me', true);
        endif;

        $request->session()->put('auth.2fa.id', $user->id);

        if($isRedirected) {
        	return redirect()->intended('/verify/two-factor');
        }

        return $this->successResponse('', [
            'redirectTo' => '/verify/two-factor'
        ]);
    }
}