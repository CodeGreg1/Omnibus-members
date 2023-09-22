<?php

namespace Modules\Profile\Services\SocialLogin;

use Laravel\Socialite\Contracts\User as SocialUser;

trait SocialLoginParseUserFullName
{	
	/**
     * Parse User's name from his social network account.
     *
     * @param SocialUser $user
     * @return array
     */
    public function parseUserFullName(SocialUser $user)
    {
        $name = $user->getName();

        if(strpos($name, " ") !== false):
            return explode(" ", $name, 2);
        endif;

        return [$name, ''];
    }
}