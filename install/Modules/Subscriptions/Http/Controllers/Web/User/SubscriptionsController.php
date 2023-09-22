<?php

namespace Modules\Subscriptions\Http\Controllers\Web\User;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Events\SubscriptionCancelled;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;
use Modules\Subscriptions\Http\Requests\CancelUserSubscriptionRequest;
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
     * Display a listing of resources.
     *
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('user.subscriptions.index');

        return view('subscriptions::user.subscription.index', [
            'pageTitle' => __('My subscriptions'),
            'gateways' => collect(array_merge(Cashier::getActiveClients(), [
                (object) [
                    'key' => 'wallet',
                    'name' => 'Wallet'
                ]
            ]))
        ]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('user.subscriptions.datatable');

        $extension = intval(setting('subscription_expiration_extension'));

        return DataTables::eloquent(
            $request->user()->subscriptions()->with(['subscribable'])
                ->when($request->get('queryValue'), function ($query, $search) {
                    $query->whereHas('items', function ($query) use ($search) {
                        $query->whereHas('price', function ($query) use ($search) {
                            $query->whereHas('package', function ($query) use ($search) {
                                $query->where('name', 'like', '%' . $search . '%');
                            });
                        });
                    });
                })
                ->when($request->get('type'), function ($query, $type) {
                    $query->when($type === 'recurring', function ($query) {
                        $query->where('recurring', 1);
                    })->when($type === 'onetime', function ($query) {
                        $query->where('recurring', 0);
                    });
                })
                ->when($request->get('gateway'), function ($query, $gateway) {
                    $query->whereIn('gateway', explode(',', $gateway));
                })
                ->when($request->get('status'), function ($query, $status) use ($extension) {
                    $query->when($status === 'trialing', function ($query) {
                        $query->onTrial();
                    })
                        ->when($status === 'active', function ($query) use ($extension) {
                            $query->where(function ($q) {
                                $q->where('ended_at', '>=', now())
                                    ->orWhereNull('ended_at');
                            })
                                ->where(function ($q) {
                                    $q->where('ends_at', '>=', now())
                                        ->orWhereNull('ends_at');
                                })
                                ->whereNull('canceled_at')
                                ->whereNull('trial_ends_at');
                        })
                        ->when($status === 'past_due', function ($query) use ($extension) {
                            $query->where(function ($q) {
                                $q->where('ended_at', '>=', now())
                                    ->orWhereNull('ended_at');
                            })
                                ->whereBetween('ends_at', [now(), now()->addDays($extension)])
                                ->whereNull('canceled_at')
                                ->whereNull('trial_ends_at');
                        })
                        ->when($status === 'canceled', function ($query) {
                            $query->whereNotNull('canceled_at')->where('canceled_at', '<=', now());
                        })
                        ->when($status === 'ended', function ($query) use ($extension) {
                            $query->whereNull('canceled_at')
                                ->where('ends_at', '<', now()->subDays($extension))
                                ->where(function ($q) {
                                    $q->where('ended_at', '<', now())
                                        ->orWhereNull('ended_at');
                                });
                        });
                })
        )
            ->addColumn('subscriber', function ($row) {
                if ($row->subscribable->name) {
                    return $row->subscribable->name;
                }

                return $row->subscribable->email;
            })
            ->addColumn('status_display', function ($row) {
                return $row->getStatusDisplay();
            })
            ->addColumn('status', function ($row) {
                return $row->getStatus();
            })
            ->addColumn('package', function ($row) {
                return $row->getPackageName();
            })
            ->addColumn('total', function ($row) {
                return $row->getTotal(true, $row->item->currency);
            })
            ->addColumn('unit_price', function ($row) {
                return $row->getUnitPrice(false, $row->item->currency);
            })
            ->addColumn('is_free', function ($row) {
                return $row->item->price->price ? false : true;
            })
            ->addColumn('can_cancel', function ($row) {
                return $row->valid() && is_null($row->canceled_at) && !$row->ended();
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    if ($sortValue[0] === 'name') {
                        $query->orderBy(User::select('first_name')->whereColumn('subscriptions.subscribable_id', 'users.id'), $sortValue[1]);
                    } else {
                        $query->orderBy($sortValue[0], $sortValue[1]);
                    }
                }
            })
            ->toJson();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $user = auth()->user();

        $subscription = $user->subscriptions()
            ->findOrFail($id);

        $this->authorize('view', $subscription);

        $totalSpent = $subscription->getTotalPayments();

        return view('subscriptions::user.subscription.show', [
            'pageTitle' => __('Subscrption details'),
            'user' => $user,
            'subscription' => $subscription,
            'totalSpent' => $totalSpent,
            'gateways' => Cashier::getActiveClients(),
            'status' => $subscription->getStatus()
        ]);
    }

    public function resume(Request $request, $id)
    {
        $this->authorize('user.subscriptions.resume');

        $subscription = $this->subscriptions->findOrFail($id);

        return $subscription;
    }

    public function cancel(CancelUserSubscriptionRequest $request, $id)
    {
        $subscription = auth()->user()->subscriptions()->findOrFail($id);

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

        return $this->successResponse(__('Subscription cancelled'), [
            'redirectTo' => route('user.subscriptions.show', $subscription)
        ]);
    }
}