<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Modules\Auth\Services\TwoFactor\Authy;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\EnableProfileTwoFactorRequest;

class EnableProfileTwoFactorController extends BaseController
{
    /**
     * @var UserRepository $users
     */
    protected $users;

    /**
     * @var Authy $authy
     */
    protected $authy;

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
     * Enable profile two factor
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function perform(EnableProfileTwoFactorRequest $request)
    {
        $status = false;
        $user = auth()->user();

        if($user->authy_status != 1) {

            $check = $this->getExistingUserByAuthyInfo(
                $request->get('country_code'), 
                $request->get('phone_number')
            );

            if( !is_null($check) ) {
                return $this->errorResponse(__('Phone number is already exists from another user. Please try another.'));
            }

            $status = false;

            $register = $this->authy->register(
                auth()->user()->email, 
                $request->get('phone_number'),
                $request->get('country_code')
            );

            if ( $register->ok() ) {
                $authyId = $register->id();

                $this->users->update($this->users->find(auth()->id()), [
                    'authy_status' => false,
                    'authy_id' => $authyId,
                    'authy_country_code' => $request->get('country_code'),
                    'authy_phone' => $request->get('phone_number')
                ]);

                $status = true;
            }
        }

        return $this->successResponse('success', ['status' => $status ]);
    }

    /**
     * Get existing user by authy country code and phone number
     * 
     * @param $countryCode
     * @param $phoneNumber
     * 
     * @return UserRepository|null
     */
    protected function getExistingUserByAuthyInfo($countryCode, $phoneNumber) 
    {   
        return $this->users
                ->where('authy_country_code', $countryCode)
                ->where('authy_phone', $phoneNumber)
                ->first();
    }
}
