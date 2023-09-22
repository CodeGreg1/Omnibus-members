<?php

namespace Modules\Profile\Repositories;

use Modules\Profile\Models\SocialLogin;
use Laravel\Socialite\Contracts\User as SocialUser;
use Torann\LaravelRepository\Repositories\AbstractRepository;
use Modules\Profile\Services\SocialLogin\SocialLoginGetAvatarSize;

class SocialLoginRepository extends AbstractRepository
{
	use SocialLoginGetAvatarSize;
	
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = SocialLogin::class;

    /**
     * Associate account details returned from social network
     * to user with provided user id.
     *
     * @param $userId
     * @param $provider
     * @param SocialUser $user
     * 
     * @return mixed
     */
    public function associateSocialAccountForUser($userId, $provider, SocialUser $user) 
    {
    	return $this->create([
    		'user_id' => $userId,
    		'provider' => $provider,
    		'provider_id' => $user->getId(),
    		'avatar' => $this->getSocialAvatar($provider, $user)
    	]);
    }

    /**
     * Disconnect associated social
     * 
     * @param int $userId
     * @param string $provider
     *
     * @return mixed
     */
    public function disconnect($userId, $provider) 
    {
        return SocialLogin::where('user_id', $userId)->where('provider', $provider)->delete();
    }

    /**
     * Find by user id and provider
     * 
     * @param int $userId
     * @param string $provider
     *
     * @return null|SocialLogin
     */
    public function findByUserIdAndProvider($userId, $provider) 
    {
        return SocialLogin::where('user_id', $userId)->where('provider', $provider)->first();
    }
}