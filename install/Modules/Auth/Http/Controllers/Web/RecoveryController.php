<?php

namespace Modules\Auth\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Auth\Http\Requests\RecoveryRequest;
use Modules\Auth\Support\Recovery\RecoverySession;
use Modules\Base\Http\Controllers\Web\BaseController;

class RecoveryController extends BaseController
{
    use RecoverySession;

    /**
     * @var UsersRepository
     */
    protected $users;

    public function __construct(UserRepository $users) 
    {
        $this->users = $users;

        parent::__construct();
    }

    /**
     * Display recovery page.
     * 
     * @return Renderable
     */
    public function show()
    {
        return view('auth::recovery', [
            'pageTitle' => __('Account Recovery')
        ]);
    }


    /**
     * Handle recovering account using downloaded code
     * 
     * @param RecoveryRequest $request
     * 
     * @return Response
     */
    public function perform(RecoveryRequest $request)
    {
        $this->handleUpadingRecoveryCodes($request);
        
        $this->setRecoverySession();

        return $this->successResponse(__('Account recovered successfully.'), [
            'redirectTo' => '/profile/security#2fa'
        ]);
    }

    /**
     * Handling updating account recovery code
     * 
     * @param Request $request
     * 
     * @return mixed
     */
    protected function handleUpadingRecoveryCodes(Request $request) 
    {
        $recoveryCodes = auth()->user()->recovery_codes;

        // Remove used recovery code
        $leftRecoveryCodes = array_diff( $recoveryCodes, [ $request->get('recovery_code') ] );

        $this->users->update($this->users->find(auth()->id()), [
            'recovery_codes' =>  json_encode( array_values( $leftRecoveryCodes ) )
        ]);
    }
}
