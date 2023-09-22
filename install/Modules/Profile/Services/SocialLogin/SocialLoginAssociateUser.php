<?php

namespace Modules\Profile\Services\SocialLogin;

use Illuminate\Support\Str;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Repositories\CountryRepository;
use Modules\Base\Support\Location\VisitorLocation;
use Laravel\Socialite\Contracts\User as SocialUser;
use Modules\Profile\Repositories\SocialLoginRepository;
use Modules\Profile\Services\SocialLogin\SocialLoginGetAvatarSize;
use Modules\Profile\Services\SocialLogin\SocialLoginParseUserFullName;

trait SocialLoginAssociateUser
{	
	use SocialLoginParseUserFullName, SocialLoginGetAvatarSize;

	/**
	 * UserRepository class
     * 
     * @var UserRepository
     */
	protected $users;

	/**
     * Required to instantiate this trait to the controller using this trait
     * so that this repository is accessible this trait
     * 
     * @var SocialLoginRepository
     */
    protected $socialLogins;

    /**
     * @param UserRepository $users
     * @param SocialLoginRepository $socialLogins
     */
	public function __construct(
        UserRepository $users, 
        SocialLoginRepository $socialLogins) 
	{
		$this->users = $users;
		$this->socialLogins = $socialLogins;
	}

    /**
     * Handle on associating user
     * 
     * @param SocialUser $socialUser
     * @param string $provider
     */
	public function associateUser(SocialUser $socialUser, $provider) 
	{
		$user = $this->users->findByEmail($socialUser->getEmail());

        if(!$user):
        	// Create user from social if not found in our database
            list($firstName, $lastName) = $this->parseUserFullName($socialUser);

            // get visitor location
            $address = (new VisitorLocation)->get();

            // get country by country name
            $country = (new CountryRepository)->findBy('name', $address->countryName);

            $user = $this->users->create([
                'email' => $socialUser->getEmail(),
                'password' => Str::random(10),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'avatar' => $this->getSocialAvatar($provider, $socialUser),
                'email_verified_at' => now(),
                'country_id' => $country->id
            ]);

            // Assign user role
            if(setting('registration_role')) {
                $user->assignRole(setting('registration_role'));
            }
        endif;

        // Associate social account to user account
        $this->socialLogins->associateSocialAccountForUser($user->id, $provider, $socialUser);

        return $user;
	}
}