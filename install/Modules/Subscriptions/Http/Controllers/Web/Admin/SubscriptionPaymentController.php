<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;

class SubscriptionPaymentController extends BaseController
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
        $this->authorize('admin.subscriptions.payments.index');

        return view('subscriptions::admin.payment.index', [
            'pageTitle' => __('Payments'),
            'gateways' => collect(array_merge(Cashier::getActiveClients(), [
                (object) [
                    'key' => 'wallet',
                    'name' => 'Wallet'
                ]
            ]))
        ]);
    }
}