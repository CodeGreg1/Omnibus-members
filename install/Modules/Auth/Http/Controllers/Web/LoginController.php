<?php

namespace Modules\Auth\Http\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Auth\Events\LoggedIn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Modules\Auth\Http\Requests\LoginRequest;
use Hexadog\ThemesManager\Facades\ThemesManager;
use Modules\Auth\Services\Login\ThrottlesLogins;
use Modules\Auth\Services\TwoFactor\TwoFactorTrait;
use Modules\Base\Http\Controllers\Web\BaseController;

class LoginController extends BaseController
{
    use ThrottlesLogins, TwoFactorTrait;
    
    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {
        return view('auth::login', [
            'pageTitle' => __('Login')
        ]);
    }

    /**
     * Handle account login request
     * 
     * @param RegisterRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        // Check login too many attempts
        if (setting('throttle_login') && $this->hasTooManyLoginAttempts($request)) {
            return $this->errorResponse($this->sendLockoutResponse($request));
        }

        if (!Auth::validate($credentials)):

            if(setting('throttle_login')) {
                // If login attempt was not successful will increment the number of login attempts
                // If user surpasses the maximum number of attempts it will get locked out
                $this->incrementLoginAttempts($request);
            }

            return $this->errorResponse(
                __('These credentials do not match our records.')
            );
        endif;

        $user = Auth::getProvider()
            ->retrieveByCredentials($credentials);

        if($user->isBanned()):
            return $this->errorResponse(
                __('Your account is banned by the administrator. Please contact support for further assistance.')
            );
        endif;

        if(setting('remember_me') && $request->get('remember')):
            Auth::setRememberDuration(setting('remember_me_lifetime', 30) * 1440);
        endif;

        Auth::login($user, ($request->get('remember') && setting('remember_me')));

        return $this->authenticated($request, $user);
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        if(setting('throttle_login')) {
            $this->clearLoginAttempts($request);
        }
        
        if(setting('two_factor') && $user->present()->twoFactorStatus == 'on'){
            return $this->logoutAndRedirectToTwoFactor($request, $user);
        }

        event(new LoggedIn($user));

        return $this->successResponse(__('Successfully login.'), [
            'redirectTo' => setting('auth_path_redirect_to', '/dashboard')
        ]);
    }
}
