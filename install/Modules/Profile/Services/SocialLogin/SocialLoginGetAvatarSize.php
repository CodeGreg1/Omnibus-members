<?php

namespace Modules\Profile\Services\SocialLogin;

use Laravel\Socialite\Contracts\User as SocialUser;

trait SocialLoginGetAvatarSize
{	
	/**
     * Get appropriate image size for a specific provider.
     *
     * @param $provider
     * @param SocialUser $socialUser
     * @return mixed|string
     */
    public function getSocialAvatar($provider, SocialUser $socialUser)
    {
        if ($provider == 'facebook') {
            return str_replace('width=1920', 'width=150', $socialUser->avatar_original);
        }

        if ($provider == 'google') {
            return $socialUser->avatar_original . '?sz=150';
        }

        if ($provider == 'twitter') {
            return str_replace('_normal', '_200x200', $socialUser->getAvatar());
        }

        return $socialUser->getAvatar();
    }
}