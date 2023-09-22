<?php

namespace Modules\Subscriptions\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Payments\Services\Payment;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Events\SubscriptionPaymentCompleted;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;

class SubscriptionManualPaymentController extends BaseController
{
    /**
     * @var SubscriptionsRepository
     */
    protected $subscriptions;

    public function __construct(SubscriptionsRepository $subscriptions)
    {
        parent::__construct();

        $this->subscriptions = $subscriptions;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, $id)
    {
        $subscription = auth()->user()->subscriptions()->findOrFail($id);
        $wallet = auth()->user()->getWalletByCurrency($subscription->item->currency);
        if ($this->processsablePayment($subscription)) {
            return redirect(route('user.subscriptions.show', $subscription));
        }

        return view('subscriptions::user.subscription.manual-payment-checkout', [
            'pageTitle' => __('Subscription manual checkout'),
            'subscription' => $subscription,
            'wallet' => $wallet
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $subscription = $request->user()->subscriptions()->findOrFail($id);
        if ($this->processsablePayment($subscription)) {
            return $this->errorResponse(__('Unprocessable payment.'));
        }

        $item = $subscription->item;
        $wallet = $request->user()->getWalletByCurrency($item->currency);

        if ($wallet && ($wallet->balance > $item->total)) {
            DB::transaction(function () use ($subscription, $item, $wallet) {
                $ends_at = now()->add(
                    $item->price->term->interval,
                    $item->price->term->interval_count
                )->format('Y-m-d H:i:s');

                $subscription->subscribable->unLoadWallet($wallet, $item->total);

                Payment::make($subscription, [
                    'transaction_id' => uniqid('trx-'),
                    'currency' => $item->currency,
                    'gateway' => $subscription->gateway,
                    'total' => $item->total
                ]);

                $subscription->fill([
                    'trial_ends_at' => null,
                    'starts_at' => $subscription->ends_at,
                    'ends_at' => $ends_at
                ])->save();

                SubscriptionPaymentCompleted::dispatch($subscription);
            }, 3);

            return $this->successResponse(__('Subscription payment success'), [
                'redirectTo' => route('user.subscriptions.show', $subscription)
            ]);
        } else {
            return $this->errorResponse(__('Insufficient balance.'));
        }
    }

    public function processsablePayment($subscription)
    {
        if ($subscription->onGracePeriod()) {
            return false;
        }

        if (24 < $subscription->getLastEndedTotalHours()) {
            return false;
        }

        return true;
    }
}
