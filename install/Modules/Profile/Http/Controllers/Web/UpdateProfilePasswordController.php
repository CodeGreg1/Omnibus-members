<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Profile\Events\ProfilePasswordUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\UpdateProfilePasswordRequest;
use Modules\Profile\Repositories\ProfilePasswordChangeRepository;

class UpdateProfilePasswordController extends BaseController
{
    /**
     * @var UserRepository $users
     */
    protected $users;

    /**
     * @var ProfilePasswordChangeRepository $users
     */
    protected $profilePasswordChanges;

    /**
     * @param UserRepository $users
     * @param ProfilePasswordChangeRepository $profilePasswordChanges
     */
    public function __construct(UserRepository $users, ProfilePasswordChangeRepository $profilePasswordChanges) 
    {
        $this->users = $users;

        $this->profilePasswordChanges = $profilePasswordChanges;

        parent::__construct();
    }

    /**
     * Update profile details
     * 
     * @param UpdateProfileTimezoneRequest $request
     * 
     * @return JsonResponse
     */
    public function update(UpdateProfilePasswordRequest $request)
    {
        $redirectTo = null;

        $user = $this->users->find(auth()->id());
        
        $attributes = [
            'password' => $request->get('password')
        ];

        if($user->invited) {
            $attributes['invited'] = null;
            $redirectTo = '/dashboard';
        }

        $user = $this->users->update($user, $attributes);

        $this->handleSavingProfilePasswordChanges();

        event(new ProfilePasswordUpdated());

        return $this->successResponse(__('Account password updated successfully.'), [
            'redirectTo' => $redirectTo
        ]);
    }

    /**
     * Handle saving profile password changes
     * 
     * @return void
     */
    protected function handleSavingProfilePasswordChanges() 
    {
        $this->profilePasswordChanges->create([
            'user_id' => auth()->id(),
            'last_change' => Carbon::now()
        ]);
    }
}
