<?php

namespace Modules\Auth\Http\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Auth\Events\LoggedIn;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Services\TwoFactor\Authy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Auth\Http\Requests\VerifyTwoFactorRequest;

class TwoFactorController extends BaseController
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
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {
        return view('auth::2fa', [
            'pageTitle' => __('Two-factor verification')
        ]);
    }

    public function verify(VerifyTwoFactorRequest $request) 
    {
        $user = $this->users->find(session('auth.2fa.id'));

        if(!$user){
            return $this->successResponse('login', [
                'redirectTo' => '/login'
            ]);
        }

        $verfiy = $this->authy->verifyToken($user->authy_id, $request->get('authy_token'));

        if($verfiy->ok()){

            if(setting('remember_me') && session('auth.remember_me')):
                Auth::setRememberDuration(
                    setting('remember_me_lifetime', 30) * 1440
                );
            endif;
            
            Auth::login($user, ( session('auth.remember_me') && setting('remember_me') ) );

            event(new LoggedIn($user));

            return $this->successResponse('login', [
                'redirectTo' => setting('auth_path_redirect_to', '/dashboard')
            ]);
        }

        return $this->errorResponse(__('Authy token is incorrect. Please try again later.'));
    }
}
