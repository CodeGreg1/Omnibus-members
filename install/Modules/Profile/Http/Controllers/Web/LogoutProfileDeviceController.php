<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Modules\Users\Repositories\UserRepository;
use Modules\Profile\Events\ProfileDeviceLogout;
use Modules\Profile\Repositories\SessionRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class LogoutProfileDeviceController extends BaseController
{
    /**
     * @var SessionRepository
     */
    protected $sessions;

    /**
     * @var UserRepository
     */
    protected $users;

    public function __construct(SessionRepository $sessions, UserRepository $users) 
    {
        $this->sessions = $sessions;

        $this->users = $users;

        parent::__construct();
    }

    /**
     * Logout profile device
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function perform(Request $request)
    {
        $this->authorize('profile.device.logout');

        $this->handleInvalidatingSession($request);

        return $this->handleResponse($request);
    }

    /**
     * Invalidate selected device session
     * 
     * @param Request $request
     * 
     * @return void
     */
    protected function handleInvalidatingSession(Request $request) 
    {
        $this->sessions->delete($request->get('id'));

        $this->users->update($this->users->find(auth()->id()), ['remember_token' => null]);
    }

    /**
     * Handle response
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    protected function handleResponse(Request $request) 
    {
        $data = ['session_id' => $request->get('id')];

        if ( $request->get('id') == session()->getId() ) {
            
            event(new ProfileDeviceLogout());
            
            $data = array_merge($data, [
                'redirectTo' => '/login'
            ]);

            session()->invalidate();
        }

        return $this->successResponse(__('Device logout successfully.'), $data);
    }
}
