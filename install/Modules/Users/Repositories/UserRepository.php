<?php

namespace Modules\Users\Repositories;

use App\Models\User;
use Modules\Roles\Support\RoleType;
use Modules\Users\Support\UserStatus;
use Illuminate\Database\Eloquent\Builder;
use Modules\Base\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{   
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = User::class;

    /**
     * Get all admins
     * 
     * @return User
     */
    public function allUsers() 
    {
        return $this->getModel()
            ->with(['roles'])
            ->whereHas('roles', function (Builder $query) {
                $query->where('type', RoleType::USER);
            })->get();
    }

    /**
     * Get all admins
     * 
     * @return User
     */
    public function allAdmins() 
    {
        return $this->getModel()
            ->with(['roles'])
            ->whereHas('roles', function (Builder $query) {
                $query->where('type', RoleType::ADMIN);
            })->get();
    }
    
    /**
     * Find user by email.
     *
     * @param $email
     * 
     * @return null|User
     */
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * Find user registered via social network.
     *
     * @param $provider string Provider used for authentication.
     * @param $providerId string Provider's unique identifier for authenticated user.
     * 
     * @return mixed
     */
    public function findBySocialId($provider, $providerId)
    {
        return User::leftJoin('social_logins', 'users.id', '=', 'social_logins.user_id')
            ->select('users.*')
            ->where('social_logins.provider', $provider)
            ->where('social_logins.provider_id', $providerId)
            ->first();
    }

    /**
     * Remove user avatar
     * 
     * @param int $userId
     * 
     * @return boolean
     */
    public function removeAvatar($userId) 
    {
        return $this->find($userId)->update([
            'avatar' => null
        ]);
    }

    /**
     * Multi-delete users
     * 
     * @param array $ids
     * 
     * @return boolean|null
     */
    public function multiDelete($ids) 
    {
        return (new $this->model)->whereIn('id', $ids)->delete();
    }

    /**
     * Multi-enable users
     * 
     * @param array $ids
     * 
     * @return boolean|null
     */
    public function multiEnable(array $ids) 
    {
        return (new $this->model)->whereIn('id', $ids)->update([
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now()
        ]);
    }

    /**
     * Multi-ban users
     * 
     * @param array $ids
     * 
     * @return boolean|null
     */
    public function multiBan(array $ids) 
    {
        return (new $this->model)->whereIn('id', $ids)->update([
            'status' => UserStatus::BANNED
        ]);
    }

    /**
     * Multi-confirm users
     * 
     * @param array $ids
     * 
     * @return boolean|null
     */
    public function multiConfirm(array $ids) 
    {
        return (new $this->model)->whereIn('id', $ids)->update([
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now()
        ]);
    }

    /**
     * Get user role
     * 
     * @param int $id
     * 
     * @return Role|null
     */
    public function role($id) 
    {
        return (new $this->model)->find($id)->roles->first();
    }

    /**
     * Query user's only active record
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function onlyActive() 
    {
        $this->newQuery();

        return $this->query->where('status', UserStatus::ACTIVE);
    }

    /**
     * Update users locale to default (this is useful when deleting the language)
     * 
     * @var string|null $currentLocale
     * 
     * @return void
     */
    public function updateUsersLocaleToDefault($currentLocale=null) 
    {
        $query = (new $this->model);

        if(!is_null($currentLocale)) {
            $query = $query->where('locale', $currentLocale);
        }
        
        $query->update([
            'locale' => setting('locale')
        ]);
    }

}