<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Profile\Events\ProfilePhotoRemoved;
use Modules\Profile\Events\ProfilePhotoUpdated;
use Modules\Profile\Services\UserAvatarUploader;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\UpdateProfileAvatarRequest;

class UpdateProfileAvatarController extends BaseController
{
    /**
     * @var UserRepository $users
     */
    protected $users;

    /**
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users) 
    {
        $this->users = $users;

        parent::__construct();
    }

    /**
     * Upload and update user's avatar.
     * 
     * @param UpdateProfileAvatarRequest $request
     * 
     * @return JsonResponse
     */
    public function update(UpdateProfileAvatarRequest $request, UserAvatarUploader $avatar)
    { 
        $path = $avatar->upload($request->get('avatar'));

        if($path) {
            return $this->handleAvatarUpdate($path, $avatar);
        }

        return $this->errorResponse(__('Avatar cannot be updated. Please try again.'));
    }

    /**
     * Handle removing user's avatar
     * 
     * @return JsonResponse
     */
    public function remove() 
    {
        $this->authorize('profile.avatar-remove');
        
        $this->users->removeAvatar(auth()->id());

        event(new ProfilePhotoRemoved());

        return $this->successResponse(__('Account avatar removed successfully.'), [
            'avatar' => setting('default_profile_photo')
        ]);
    }

    /**
     * Handle avatar update
     * 
     * @param string $path
     * @param UserAvatarUploader $avatar
     * 
     * @return JsonResponse
     */
    protected function handleAvatarUpdate($path, UserAvatarUploader $avatar) 
    {
        if(auth()->user()->avatar) {
            $avatar->delete(auth()->user()->avatar);
        }

        $user = $this->users->update($this->users->find(auth()->id()), ['avatar' => $path]);

        event(new ProfilePhotoUpdated());

        return $this->successResponse(__('Account avatar successfully updated.'), [
            'path' => url($path)
        ]);
    }
}
