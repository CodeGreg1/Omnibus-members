<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;

class SubscriptionDatatableController extends BaseController
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

    public function handle(Request $request)
    {
        $this->authorize('admin.subscriptions.datatable');

        $extension = intval(setting('subscription_expiration_extension'));

        return DataTables::eloquent(
            $this->subscriptions->getModel()->where('subscribable_type', 'App\Models\User')
                ->has('subscribable')
                ->when($request->get('queryValue'), function ($query, $search) {
                    $query->whereHas('subscribable', function ($query) use ($search) {
                        $query->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%" . $search . "%")
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('gateway', 'like', '%' . $search . '%');
                    })->orWhereHas('items', function ($query) use ($search) {
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
                                ->whereBetween('ends_at', [now()->subDays($extension), now()])
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
            ->addColumn('user', function ($row) {
                if (!$row->subscribable) {
                    return '';
                }
                if ($row->subscribable->name) {
                    return $row->subscribable->name;
                } else {
                    return $row->subscribable->email;
                }
            })
            ->addColumn('created', function ($row) {
                return $row->created_at_display;
            })
            ->addColumn('status', function ($row) {
                return $row->getStatus();
            })
            ->addColumn('status_display', function ($row) {
                return $row->getStatusDisplay();
            })
            ->addColumn('package', function ($row) {
                return $row->getPackageName();
            })
            ->addColumn('is_free', function ($row) {
                return $row->item->price->price ? false : true;
            })
            ->addColumn('price', function ($row) {
                return $row->getTotal(true, $row->item->currency);
            })
            ->addColumn('term_description', function ($row) {
                return $row->getTermDescription();
            })
            ->addColumn('can_cancel', function ($row) {
                return $row->valid() && is_null($row->canceled_at) && !$row->ended();
            })
            ->editColumn('subscribable.email', function($row) {
                if (!$row->subscribable) {
                    return '';
                }
                if(env('APP_DEMO')) {
                    return protected_data($row->subscribable->email, 'email');
                }
                return $row->subscribable->email;
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
}