<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Modules\Cashier\Facades\Cashier;
use Modules\Payments\Services\Payment;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Events\SubscriptionCancelled;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;
use Modules\Subscriptions\Http\Requests\CancelSubscriptionRequest;
use Modules\Subscriptions\Http\Requests\ResumeSubscriptionRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class SubscriptionsController extends BaseController
{
    /**
     * @var SubscriptionsRepository
     */
    protected $subscriptions;

    /**
     * @var AvailableCurrenciesRepository
     */
    protected $currencies;

    public function __construct(
        SubscriptionsRepository $subscriptions,
        AvailableCurrenciesRepository $currencies
    ) {
        parent::__construct();

        $this->subscriptions = $subscriptions;
        $this->currencies = $currencies;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.subscriptions.index');

        return view('subscriptions::admin.subscription.index', [
            'pageTitle' => __('Subscriptions'),
            'gateways' => collect(array_merge(Cashier::getActiveClients(), [
                (object) [
                    'key' => 'wallet',
                    'name' => 'Wallet'
                ]
            ]))
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $subscription = $this->subscriptions
            ->findOrFail($id);

        $this->authorize('view', $subscription);

        return view('subscriptions::admin.subscription.show', [
            'pageTitle' => __('Subscrption details'),
            'subscription' => $subscription,
            'totalSpent' => $subscription->getTotalPayments(),
            'nextInvoiceDate' => 4,
            'status' => $subscription->getStatus()
        ]);
    }

    public function cancel(CancelSubscriptionRequest $request, $id)
    {
        $subscription = $this->subscriptions->findOrFail($id);

        $this->authorize('cancel', $subscription);

        if ($subscription->canceled()) {
            return $this->errorResponse(__('Subscription is already cancelled'));
        }

        if (!$subscription->valid()) {
            return $this->errorResponse(__('Subscription is already ended'));
        }

        $response = $subscription->cancel();
        if (!$response) {
            return $this->errorResponse(__('Something went wrong. Subscription/s not cancelled. Try again later'));
        }

        SubscriptionCancelled::dispatch($subscription);

        return $this->successResponse(__(
            'Subscription of :user cancelled',
            ['user' => $subscription->subscribable->full_name]
        ), [
            'redirectTo' => route('admin.subscriptions.show', $subscription)
        ]);
    }

    public function resume(ResumeSubscriptionRequest $request, $id)
    {
        $subscription = $this->subscriptions->findOrFail($id);
        $subscription->resume($request->mode);

        return $this->successResponse(__(
            'Subscription of :user resumed',
            ['user' => $subscription->subscribable->full_name]
        ), [
            'redirectTo' => route('admin.subscriptions.show', $subscription)
        ]);
    }
}