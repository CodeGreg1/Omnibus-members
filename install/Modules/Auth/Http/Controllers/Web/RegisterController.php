<?php

namespace Modules\Auth\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Users\Support\UserStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Base\Http\Controllers\Web\BaseController;

class RegisterController extends BaseController
{
    /**
     * Registration controller instance.
     * 
     * @param UserRepository $users
     */
    protected $users;

    public function __construct(UserRepository $users) 
    {
        $this->users = $users;

        parent::__construct();
    }

    /**
     * Display register page.
     * 
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('auth::register', [
            'pageTitle' => __('Registration')
        ]);
    }

    /**
     * Handle account registration request
     * 
     * @param RegisterRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->users->create(array_merge(
            $request->validated(),
            [
                'email_verified_at' => (setting('registration_email_confirmation') ? null : now())
            ]
        ));

        if(setting('registration_role')) {
            $user->assignRole(setting('registration_role'));
        }

        event(new Registered($user));

        auth()->login($user);

        return $this->successResponse(
            __('Account created successfully.'), 
            ['redirectTo' => setting('registration_path_redirect_to', '/')], 
            Response::HTTP_CREATED
        );
    }
}
