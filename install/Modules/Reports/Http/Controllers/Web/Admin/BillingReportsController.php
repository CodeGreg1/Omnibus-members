<?php

namespace Modules\Reports\Http\Controllers\Web\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Modules\Reports\Support\BillingReport;
use Illuminate\Contracts\Support\Renderable;
use Modules\Subscriptions\Models\Subscription;
use Modules\Base\Http\Controllers\Web\BaseController;

class BillingReportsController extends BaseController
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
        $this->authorize('admin.reports.billing');

        return view('reports::billing.index', [
            'pageTitle' => __('Billing reports')
        ]);
    }

    public function report(Request $request)
    {
        $overview = new BillingReport;
        return $overview->get($request->get('start'), $request->get('end'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('admin.reports.billing.datatable');

        return DataTables::eloquent(
            Subscription::where('subscribable_type', 'App\Models\User')
                ->has('subscribable')
                ->when($request->get('status'), function ($query, $status) use ($request) {
                    $query->when($request->get('date'), function ($query, $date) use ($status) {
                        $dates = explode(',', $date);
                        $range = [
                            Carbon::create($dates[0]),
                            Carbon::create($dates[1])
                        ];

                        $query->whereBetween('created_at', $range)
                            ->when($status === 'Active', function ($q) {
                                $q->where(function ($q) {
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
                            ->when($status === 'Trialing', function ($q) {
                                $q->onTrial();
                            })
                            ->when($status === 'Cancelled', function ($q) {
                                $q->whereNotNull('canceled_at')->where('canceled_at', '<=', now());
                            })
                            ->when($status === 'Ended', function ($q) {
                                $extension = intval(setting('subscription_expiration_extension'));
                                $q->whereNull('canceled_at')
                                    ->where('ends_at', '<', now()->subDays($extension))
                                    ->where(function ($q) {
                                        $q->where('ended_at', '<', now())
                                            ->orWhereNull('ended_at');
                                    });
                            });
                    });
                }, function ($query) use ($request) {
                    $query->when($request->get('date'), function ($query, $date) {
                        $dates = explode(',', $date);
                        $range = [
                            Carbon::create($dates[0]),
                            Carbon::create($dates[1])
                        ];

                        $query->whereBetween('created_at', $range);
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
                    return protected_data($row->subscribable->email, 'email');
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
                return $row->getTotal();
            })
            ->addColumn('term_description', function ($row) {
                return $row->getTermDescription();
            })
            ->addColumn('can_cancel', function ($row) {
                return $row->valid() && is_null($row->canceled_at);
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
                $query->orderBy('created_at', 'desc');
            })
            ->toJson();
    }
}
