<?php

namespace Modules\Subscriptions\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Payments\Models\Payment;
use Yajra\DataTables\Facades\DataTables;
use Modules\Subscriptions\Models\Subscription;
use Modules\Base\Http\Controllers\Web\BaseController;

class SubscriptionPaymentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function datatable(Request $request)
    {
        $this->authorize('user.subscriptions.payments.datatable');

        $subscription = auth()->user()->subscriptions()->find($request->subscription);

        if (!$subscription) {
            return DataTables::collection(collect([]))->toJson();
        }

        return DataTables::eloquent(
            Payment::whereHasMorph(
                'payable',
                [Subscription::class],
                function ($query) use ($request, $subscription) {
                    $query->where('payable_id', $subscription->id)
                        ->when($request->get('queryValue'), function ($query, $search) {
                            $query->whereHas('payable', function ($query) use ($search) {
                                $query->whereHas('items', function ($query) use ($search) {
                                    $query->whereHas('price', function ($query) use ($search) {
                                        $query->whereHas('package', function ($query) use ($search) {
                                            $query->where('name', 'like', '%' . $search . '%');
                                        });
                                    });
                                });
                            });
                        })
                        ->when($request->get('status'), function ($query, $status) {
                            $query->where('state', $status);
                        });
                }
            )->has('payable.subscribable')
        )
            ->addColumn('amount', function ($row) {
                return currency_format($row->total, $row->currency);
            })
            ->addColumn('description', function ($row) {
                return __('Subscription payment');
            })
            ->addColumn('state_label', function ($row) {
                return $row->state->label();
            })
            ->addColumn('state_color', function ($row) {
                return $row->state->color();
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