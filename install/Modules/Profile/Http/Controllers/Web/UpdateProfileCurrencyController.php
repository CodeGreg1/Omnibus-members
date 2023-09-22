<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Profile\Events\ProfileCurrencyUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\UpdateProfileCurrencyRequest;

class UpdateProfileCurrencyController extends BaseController
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
     * @param UpdateProfileCurrencyRequest $request
     * 
     * @return JsonResponse
     */
    public function update(UpdateProfileCurrencyRequest $request)
    {
        $this->users->update($this->users->find(auth()->id()), $request->validated());

        event(new ProfileCurrencyUpdated());

        return $this->successResponse(__('Account currency updated successfully.'));
    }
}
