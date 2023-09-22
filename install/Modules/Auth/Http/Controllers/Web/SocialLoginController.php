<?php

namespace Modules\Auth\Http\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Auth\Events\LoggedIn;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Modules\Users\Repositories\UserRepository;
use Modules\Auth\Services\TwoFactor\TwoFactorTrait;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Repositories\SocialLoginRepository;
use Modules\Profile\Events\ProfileLoginServiceConnected;
use Modules\Profile\Services\SocialLogin\SocialLoginAssociateUser;

class SocialLoginController extends BaseController
{
    use SocialLoginAssociateUser, TwoFactorTrait;

	/**
     * @var UserRepository
     */
    protected $users;

    /**
     * Required to instantiate SocialLoginRepository
     * so that the repository is accessible in SocialLoginAssociateUser trait
     * 
     * @var SocialLoginRepository
     */
    protected $socialLogins;

	public function __construct(UserRepository $users, SocialLoginRepository $socialLogins) 
	{
		$this->users = $users;
        $this->socialLogins = $socialLogins;
        parent::__construct();
	}

	public function redirectToProvider($provider) 
	{
		if(strtolower($provider) == 'facebook'):
            return Socialite::driver('facebook')->with(['auth_type' => 'rerequest'])->redirect();
        endif;

        return Socialite::driver($provider)->redirect();
	}

	/**
     * Handle response authentication provider.
     *
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        if(request()->session()->has('connect_social_provider')) {
            return $this->connectSocialToUser($provider);
        }

        if (request()->get('error')) {
            return redirect('login')
                ->withErrors(__('Something went wrong during the authentication process. Please try again.'));
        }

        $socialUser = $this->getUserFromProvider($provider);

        $user = $this->users->findBySocialId($provider, $socialUser->getId());

        if (!$user) {
            if (!setting('allow_registration')) {
                return redirect('login')
                    ->withErrors(__('New account registration is disabled.'));
            }

            if (!$socialUser->getEmail()) {
                return redirect('login')
                    ->withErrors(__('You have to provide your email address.'));
            }

            $user = $this->associateUser($socialUser, $provider);
        }

        return $this->loginAndRedirect($user);
    }

    protected function connectSocialToUser($provider) 
    {
        $socialUser = $this->getUserFromProvider($provider);

        $user = $this->users->findBySocialId($provider, $socialUser->getId());

        Auth::login($this->users->find(request()->session()->get('connect_social_user_id')));

        event(new ProfileLoginServiceConnected($provider));
        
        if (!$user) {
            if (!$socialUser->getEmail()){
                return redirect()
                    ->intended('/profile#login')
                    ->withErrors(__('You have to provide your email address.'));
            }

            if(!is_null($this->users->findByEmail( $socialUser->getEmail()))) {
                return redirect()
                    ->intended('/profile#login')
                    ->withErrors(__('Email address associated with this :provider account is already exists.', ['provider' => ucwords($provider)]));
            }

            $this->associateSocialToUser($socialUser, $provider);

            return redirect()
                ->intended('/profile#login')
                ->with('success', __(':provider account successfully added.', ['provider' => ucwords($provider)]));
        }

        return redirect()
            ->intended('/profile#login')
            ->withErrors(__(':provider account already associated with another user.', ['provider' => ucwords($provider)]));
    }

    protected function associateSocialToUser($socialUser, $provider) 
    {
        $userId = request()->session()->get('connect_social_user_id');

        $this->clearSession();

        $user = $this->users->find($userId);

        // Associate social account to user account
        $this->socialLogins->associateSocialAccountForUser($user->id, $provider, $socialUser);

        return $user;
    }

    protected function clearSession() 
    {
        request()->session()->forget('connect_social_provider');
        request()->session()->forget('connect_social_user_id');
    }

    /**
     * Get user from authentication provider.
     *
     * @param $provider
     * @return SocialUser
     */
    protected function getUserFromProvider($provider)
    {
        return Socialite::driver($provider)->user();
    }

    /**
     * Log provided user in and redirect him to intended page.
     *
     * @param $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function loginAndRedirect($user)
    {
        if ($user->isBanned()) {
            return redirect()->to('login')
                ->withErrors(__('Your account is banned by the administrator. Please contact support for further assistance.'));
        }

        if(setting('two_factor') && $user->present()->twoFactorStatus == 'on'){
            return $this->logoutAndRedirectToTwoFactor(request(), $user, true);
        }

        Auth::login($user);

        event(new LoggedIn($user));

        return redirect()->intended(setting('auth_path_redirect_to', '/dashboard'));
    }
}