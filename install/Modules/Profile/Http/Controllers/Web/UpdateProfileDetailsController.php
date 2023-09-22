<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Profile\Events\ProfileDetailsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\UpdateProfileDetailsRequest;

class UpdateProfileDetailsController extends BaseController
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
     * Update profile details
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function update(UpdateProfileDetailsRequest $request)
    {
        $profile = $this->users->find(auth()->id());

        $this->users->update($profile, $request->only('first_name', 'last_name'));

        event(new ProfileDetailsUpdated($profile));

        return $this->successResponse(__('Account details updated successfully.'));
    }
}
