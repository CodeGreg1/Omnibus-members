<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Repositories\SocialLoginRepository;
use Modules\Profile\Events\ProfileLoginServiceDisconnected;
use Modules\Profile\Http\Requests\DisconnectProfileSocialRequest;

class UpdateProfileLoginServiceController extends BaseController
{   
    /**
     * @var UserRepository $users
     */
    protected $users;

    /**
     * @var SocialLoginRepository $socialLogins
     */
    protected $socialLogins;

    /**
     * @param UserRepository $users
     * @param SocialLoginRepository $socialLogins
     */
    public function __construct(UserRepository $users, SocialLoginRepository $socialLogins) 
    {
        $this->users = $users;
        
        $this->socialLogins = $socialLogins;

        parent::__construct();
    }

    /**
     * Redirect user to a selected social provider
     *
     * @param string $provider
     * 
     * @return RedirectResponse
     */
    public function redirectToProvider($provider) 
    {
        $this->authorize('profile.social-connect');

        $this->setSession()->setLogout();

        if ( strtolower($provider) == 'facebook' ):
            return Socialite::driver('facebook')->with(['auth_type' => 'rerequest'])->redirect();
        endif;

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Disconnect to a social provider
     *
     * @param DisconnectProfileSocialRequest $request
     * 
     * @return JsonResponse
     */
    public function disconnect(DisconnectProfileSocialRequest $request) 
    {
        $this->authorize('profile.social-disconnect');
        
        $provider = $request->get('provider');

        if ( $this->socialLogins->findByUserIdAndProvider(auth()->id(), $provider) ) {

            $this->socialLogins->disconnect(auth()->id(), $provider);

            event(new ProfileLoginServiceDisconnected($provider));
            
            return $this->successResponse(__(':provider login disconnected successfully.', ['provider' => ucfirst($provider)]), [
                'route' => route('profile.social-connect', $provider),
                'caption' => $this->handleSocialProviderCaption($provider)
            ]);
        }
        
        return $this->errorResponse(__('No associated :provider login in this account.', ['provider' => ucfirst($provider)]));
    }

    /**
     * Handle social provider caption
     * 
     * @param string $provider
     * 
     * @return string
     */
    protected function handleSocialProviderCaption($provider) 
    {
        if ( $provider == 'facebook' ) {
            return auth()->user()->present()->facebookLoginCaption;
        }

        return auth()->user()->present()->googleLoginCaption;
    }

    /**
     * Set session so that when redirecting and logout the system it will remember
     * the user
     * 
     * @return $this
     */
    protected function setSession() 
    {
        request()->session()->put('connect_social_provider', 1);
        request()->session()->put('connect_social_user_id', auth()->id());

        return $this;
    }

    /**
     * Logout the user
     * 
     * @return Auth
     */
    protected function setLogout() 
    {
        Auth::logout();
    }
}
