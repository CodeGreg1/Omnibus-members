<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Profile\Events\ProfileLanguageUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\UpdateProfileLanguageRequest;

class UpdateProfileLanguageController extends BaseController
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
     * Update account profile details
     * 
     * @param UpdateProfileLanguageRequest $request
     * 
     * @return JsonResponse
     */
    public function update(UpdateProfileLanguageRequest $request)
    {
        $this->users->update(
            $this->users->find(auth()->id()), 
            $request->validated()
        );

        event(new ProfileLanguageUpdated());

        return $this->successResponse(__('Account language updated successfully.'));
    }
}
