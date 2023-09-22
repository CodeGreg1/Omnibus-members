<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\Payments\Models\Payment;
use Yajra\DataTables\Facades\DataTables;
use Modules\Subscriptions\Models\Subscription;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;

class SubscriptionPaymentDatatableController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function handle(Request $request)
    {
        if (!Gate::allows('admin.subscriptions.payments.datatable')) {
            return abort(403);
        }

        return DataTables::eloquent(
            Payment::with(['payable'])
                ->where('payable_type', 'Modules\Subscriptions\Models\Subscription')
                ->has('payable.subscribable')
                ->when($request->subscription, function ($query, $subscription) {
                    $query->where('payable_id', $subscription);
                })
                ->when($request->get('queryValue'), function ($query, $search) {
                    $query->whereHas('payable', function ($query) use ($search) {
                        $query->whereHas('subscribable', function ($query) use ($search) {
                            $query->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%" . $search . "%")
                                ->orWhere('email', 'like', '%' . $search . '%')
                                ->orWhere('gateway', 'like', '%' . $search . '%');
                        })
                            ->orWhereHas('items', function ($query) use ($search) {
                                $query->whereHas('price', function ($query) use ($search) {
                                    $query->whereHas('package', function ($query) use ($search) {
                                        $query->where('name', 'like', '%' . $search . '%');
                                    });
                                })->orWhere('total', 'like', '%' . $search . '%');
                            });
                    });
                })
                ->when($request->get('status'), function ($query, $status) {
                    $query->where('state', $status);
                })
                ->when($request->get('gateway'), function ($query, $gateway) {
                    $query->whereIn('gateway', explode(',', $gateway));
                })
        )
            ->addColumn('amount', function ($row) {
                return currency_format($row->total, $row->currency);
            })
            ->addColumn('description', function ($row) {
                return __('Subscription payment');
            })
            ->addColumn('subscriber', function ($row) {
                if (!$row->payable->subscribable) {
                    return '';
                }

                $subcriber = $row->payable->subscribable->full_name;
                if (!$subcriber) {
                    $subcriber = protected_data($row->payable->subscribable->email, 'email');
                }

                return $subcriber;
            })
            ->addColumn('package', function ($row) {
                return $row->payable->getPackageName();
            })
            ->addColumn('state_label', function ($row) {
                return $row->state->label();
            })
            ->addColumn('state_color', function ($row) {
                return $row->state->color();
            })
            ->editColumn('payable.subscribable.email', function($row) {
                if (!$row->payable->subscribable) {
                    return '';
                }
                if(env('APP_DEMO')) {
                    return protected_data($row->payable->subscribable->email, 'email');
                }
                return $row->payable->subscribable->email;
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    if ($sortValue[0] === 'created_at') {
                        $query->orderBy($sortValue[0], $sortValue[1]);
                    }
                }
            })
            ->toJson();
    }
}