<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\TimezoneKey;
use Modules\Base\Support\TimezoneValue;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Profile\Events\ProfileTimezoneUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\UpdateProfileTimezoneRequest;

class UpdateProfileTimezoneController extends BaseController
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
     * Update profile details
     * 
     * @param UpdateProfileTimezoneRequest $request
     * 
     * @return JsonResponse
     */
    public function update(UpdateProfileTimezoneRequest $request)
    {
        $data = $request->validated();

        $data['timezone'] = (new TimezoneValue)->get(
            $request->get('timezone')
        );

        $data['timezone_display'] = (new TimezoneKey)->get(
            $request->get('timezone')
        );

        $this->users->update(
            $this->users->find(auth()->id()),
            $data
        );

        event(new ProfileTimezoneUpdated());

        return $this->successResponse(__('Account timezone updated successfully.'));
    }
}
