<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class UserBillingController extends BaseController
{
    /**
     * @var UserRepository
     */
    protected $users;

    public function __construct(UserRepository $users)
    {
        parent::__construct();

        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle($id)
    {
        $this->authorize('admin.users.billing');

        $user = $this->users->find($id);

        return view('subscriptions::admin.user.billing', [
            'pageTitle' => __('View user'),
            'user' => $user
        ]);
    }
}