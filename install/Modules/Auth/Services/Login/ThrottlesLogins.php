<?php

namespace Modules\Auth\Services\Login;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins as ThrottlesLoginsBase;

trait ThrottlesLogins
{
	use ThrottlesLoginsBase;

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Determine how many retries are left for the user.
     *
     * @param Request $request
     * 
     * @return int
     */
    protected function retriesLeft(Request $request)
    {
        $attempts = $this->limiter()->attempts(
            $this->throttleKey($request)
        );

        return $this->maxAttempts() - $attempts + 1;
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        
        return $this->getLockoutErrorMessage($seconds);
    }

    /**
     * Get the login lockout error message.
     *
     * @param  int  $seconds
     * 
     * @return string
     */
    protected function getLockoutErrorMessage($seconds)
    {
        return trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60)
        ]);
    }

    /** @inheritDoc */
    protected function maxAttempts()
    {
        return setting(
            'auth_throttle_maximum_attempts', 
            5
        );
    }

    /** @inheritDoc */
    protected function decayMinutes()
    {
        $lockout = (int) setting(
            'auth_throttle_lockout_time', 
            5
        );

        return $lockout <= 1 ? 1 : $lockout;
    }
}