<?php

namespace Modules\Subscriptions\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class InsufficientModulePermissionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle()
    {
        $this->authorize('user.subscriptions.module-usages.unauthorized');

        return view('subscriptions::user.module-usage.insufficient-balance', [
            'pageTitle' => __('Module permission'),
            'isSubscribe' => auth()->user()->hasAnySubscription()
        ]);
    }
}