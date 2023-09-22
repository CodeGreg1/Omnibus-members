<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Profile\Events\ProfileAllDeviceLogout;
use Modules\Profile\Repositories\SessionRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class LogoutAllProfileDeviceController extends BaseController
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
     * @return JsonResponse
     */
    public function perform()
    {
        $this->authorize('profile.all-device.logout');
        
        $this->handleInvalidatingAllSessions();

        return $this->handleResponse();
    }

    /**
     * Invalidate all devices session
     * 
     * @return void
     */
    protected function handleInvalidatingAllSessions() 
    {
        $this->sessions->invalidateAllDevices();

        $this->users->update($this->users->find(auth()->id()), ['remember_token' => null]);
    }

    /**
     * Handle response
     * 
     * @return JsonResponse
     */
    protected function handleResponse() 
    {
        event (new ProfileAllDeviceLogout());

        return $this->successResponse(__('Devices logout successfully.'), [
            'redirectTo' => '/login'
        ]);
    }
}
