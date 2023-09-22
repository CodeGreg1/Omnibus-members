<?php

namespace Modules\Users\Presenters;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Modules\Base\Presenters\Presenter;
use Modules\Auth\Support\Recovery\RecoverySession;

class UserPresenter extends Presenter
{
    use RecoverySession;

    /**
     * User's name
     * 
     * @return string
     */
    public function name()
    {
        return $this->entity->first_name . ' ' . $this->entity->last_name;
    }

    /**
     * User's avatar
     * 
     * @return string
     */
    public function avatar()
    {
        if (!$this->entity->avatar) {
            return setting('default_profile_photo');
        }

        return $this->entity->avatar;
    }

    /**
     * User's last password change
     * 
     * @return string
     */
    public function lastPasswordChange()
    {
        return $this->entity->lastPasswordChange
            ? $this->entity->lastPasswordChange->lastChangeForHumans()
            : 'N/A';
    }

    /**
     * Check if user has facebook login
     * 
     * @return  boolean
     */
    public function hasFacebookLogin() 
    {
        return ! is_null( $this->entity->socialLogin->where('provider', 'facebook')->first() );
    }

    /**
     * Handle getting login route
     * 
     * @return string
     */
    public function facebookLoginRoute() 
    {
        return $this->hasFacebookLogin()
            ? ''
            : route('profile.social-connect', 'facebook');
    }

    /**
     * Handle on getting facebook login caption
     * 
     * @return string
     */
    public function facebookLoginCaption() 
    {
        return $this->hasFacebookLogin()
            ? __('Disconnect with Facebook')
            : __('Connect with Facebook');
    }

    /**
     * Handle generating class for facebook login connected status
     * 
     * @return string|null
     */
    public function facebookLoginClass() 
    {
        return $this->hasFacebookLogin()
            ? 'facebook-connected'
            : '';
    }

    /**
     * Check if user has google login
     * 
     * @return  boolean
     */
    public function hasGoogleLogin() 
    {
        return ! is_null( $this->entity->socialLogin->where('provider', 'google')->first() );
    }

    /**
     * Handle getting google login route
     * 
     * @return string|null
     */
    public function googleLoginRoute() 
    {
        return $this->hasGoogleLogin()
            ? ''
            : route('profile.social-connect', 'google');
    }

    /**
     * Handle getting google login caption
     * 
     * @return string
     */
    public function googleLoginCaption() 
    {
        return $this->hasGoogleLogin()
            ? __('Disconnect with Google')
            : __('Connect with Google');
    }

    /**
     * Handle getting google login class
     * @return string|null
     */
    public function googleLoginClass() 
    {
        return $this->hasGoogleLogin()
            ? 'google-connected'
            : '';
    }

    /**
     * Handle getting two factor status
     * 
     * @return string
     */
    public function twoFactorStatus() 
    {
        return $this->entity->authy_status
            ? 'on'
            : 'off'; 
    }

    /**
     * Handle getting two factor status icon
     * 
     * @return string
     */
    public function twoFactorStatusIcon() 
    {
        return $this->entity->authy_status
            ? 'fa-check'
            : 'fa-times'; 
    }

    /**
     * Handle getting two factor status icon color
     * 
     * @return string
     */
    public function twoFactorStatusIconColor() 
    {
        return $this->entity->authy_status
            ? 'text-success'
            : 'text-danger'; 
    }

    /**
     * Handle getting two factor disabled
     * 
     * @return string|null
     */
    public function twoFactorDisabled() 
    {
        return $this->entity->authy_status
            ? 'disabled'
            : ''; 
    }

    /**
     * Handle checking recovery logged
     * 
     * @return boolean
     */
    public function isRecoveryLogged() 
    {
        return $this->hasRecoverySession();
    }

    /**
     * Handle getting user last login
     * 
     * @return string
     */
    public function lastLogin()
    {
        if($this->entity->last_login) {
            $result = Carbon::parse($this->entity->last_login)
                ->settings([
                    'timezone' => auth()->user()->timezone]
                )
                ->diffForHumans();

            $result = str_replace(
                'minute', 
                'hours', 
                $result
            );

            $result = str_replace(
                'minute', 
                'min', 
                $result
            );

            $result = str_replace(
                'second', 
                'sec', 
                $result
            );

            $result = str_replace(
                'seconds', 
                'sec', 
                $result
            );

            return $result;
        }

        return 'N/A';
    }
}
