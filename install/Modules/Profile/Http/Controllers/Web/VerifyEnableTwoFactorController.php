<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Auth\Services\TwoFactor\Authy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Profile\Events\Profile2FAEnabled;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\VerifyEnabledTwoFactorRequest;

class VerifyEnableTwoFactorController extends BaseController
{
    /**
     * @var UserRepository $users
     */
    protected $users;

    /**
     * @var Authy $authy
     */
    protected $authy;

    /**
     * @param UserRepository $users
     * @param Authy $authy
     */
    public function __construct(
        UserRepository $users,
        Authy $authy
    ) 
    {
        $this->users = $users;
        $this->authy = $authy;

        parent::__construct();
    }

    /**
     * Verify enable two factor
     * @param Request $request
     * @return Response
     */
    public function perform(VerifyEnabledTwoFactorRequest $request)
    {
        $user = auth()->user();

        $verfiy = $this->authy->verifyToken($user->authy_id, $request->get('token'));

        if($verfiy->ok()) {
            $this->users->update($this->users->find($user->id), [
                'authy_status' => true
            ]);

            event(new Profile2FAEnabled());

            return $this->successResponse(__('Account two factor enabled successfully.'));
        }

        return $this->errorResponse(__("Can\'t verify two factor token. Please try again."));
    }
}
