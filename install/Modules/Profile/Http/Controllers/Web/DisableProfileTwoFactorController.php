<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Modules\Profile\Events\Profile2FADisabled;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\EnableProfileTwoFactorRequest;

class DisableProfileTwoFactorController extends BaseController
{
    /**
     * @var UserRepository $users
     */
    protected $users;

    /**
     * @var UserRepository $users
     */
    public function __construct(UserRepository $users) 
    {
        $this->users = $users;

        parent::__construct();
    }

    /**
     * Disable profile two factor
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function perform()
    {
        $this->authorize('profile.two-factor.disable');
        
        $this->users->update($this->users->find(auth()->id()), [
            'authy_status' => 0
        ]);

        event(new Profile2FADisabled());

        return $this->successResponse(__('Account two factor disabled successfully.'));
    }
}
