<?php

namespace Modules\Subscriptions\Http\Controllers\Web\User;

use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class SubscriptionProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('profile.billing');

        $subscription = auth()->user()->subscription();

        return view('subscriptions::billing-profile.index', [
            'pageTitle' => __('Account billing'),
            'subscription' => $subscription,
            'status' => $subscription ? $subscription->getStatus() : null
        ]);
    }
}