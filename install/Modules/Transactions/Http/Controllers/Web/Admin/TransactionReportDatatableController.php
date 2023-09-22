<?php

namespace Modules\Transactions\Http\Controllers\Web\Admin;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Wallet\Models\Wallet;
use Modules\Deposits\Models\Deposit;
use Yajra\DataTables\Facades\DataTables;
use Modules\Withdrawals\Models\Withdrawal;
use Illuminate\Contracts\Support\Renderable;
use Modules\Transactions\Models\Transaction;
use Modules\Base\Http\Controllers\Web\BaseController;

class TransactionReportDatatableController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle(Request $request)
    {
        $this->authorize('admin.transactions.reports.datatable');

        return $this->datatable($request)->toJson();
    }

    public function print(Request $request)
    {
        $this->authorize('admin.transactions.reports.datatable.print-preview');

        $request = $request->merge([
            'start' => 0,
            'limit' => -1
        ]);

        $data = $this->datatable($request)
            ->addColumn('user', function ($row) {
                return $row->user->full_name ?? $row->user->email;
            })
            ->toArray()['data'];

        return view('transactions::admin.print', [
            'data' => $data
        ])->render();
    }

    protected function datatable(Request $request)
    {
        return DataTables::eloquent(
            Transaction::withoutGlobalScope('currency')
                ->when($request->get('type'), function ($q, $type) {
                    if ($type === 'Deposit') {
                        return $q->whereIn(
                            'transactionable_type',
                            [
                                'Modules\Deposits\Models\Deposit',
                                'Modules\Wallet\Models\Wallet'
                            ]
                        )->where('description', 'LIKE', 'Deposited%');
                    } else if ($type === 'Withdrawal') {
                        return $q->whereIn(
                            'transactionable_type',
                            [
                                'Modules\Wallet\Models\Wallet',
                                'Modules\Withdrawals\Models\Withdrawal'
                            ]
                        )->where('description', 'LIKE', 'Withdraw%');
                    } else if ($type === 'Transfer') {
                        return $q->where(
                            'transactionable_type',
                            'Modules\Wallet\Models\Wallet'
                        )->where('description', 'LIKE', 'Send%')
                            ->orWhere('description', 'LIKE', 'Received%');
                    } else if ($type === 'Exchange') {
                        return $q->where(
                            'transactionable_type',
                            'Modules\Wallet\Models\Wallet'
                        )
                            ->where('description', 'LIKE', 'Subtracted')
                            ->orWhere('description', 'LIKE', 'Added');
                    } else if ($type === 'Subscription') {
                        return $q->where(
                            'transactionable_type',
                            'Modules\Subscriptions\Models\Subscription'
                        );
                    } else {
                        return $q->whereIn(
                            'transactionable_type',
                            [
                                'Modules\Deposits\Models\Deposit',
                                'Modules\Wallet\Models\Wallet',
                                'Modules\Withdrawals\Models\Withdrawal',
                                'Modules\Subscriptions\Models\Subscription'
                            ]
                        );
                    }
                }, function ($q) {
                    return $q->whereIn(
                        'transactionable_type',
                        [
                            'Modules\Deposits\Models\Deposit',
                            'Modules\Wallet\Models\Wallet',
                            'Modules\Withdrawals\Models\Withdrawal',
                            'Modules\Subscriptions\Models\Subscription'
                        ]
                    );
                })
                ->when($request->get('user'), function ($query, $user) {
                    return $query->where('user_id', $user);
                })
                ->when($request->get('date'), function ($query, $date) {
                    $range = collect(explode(',', $date))->map(function ($d) {
                        return Carbon::create($d);
                    })->toArray();

                    return $query->whereBetween('created_at', $range);
                })
                ->with(['transactionable', 'user'])
        )
            ->addColumn('date', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->addColumn('initial_balance', function ($row) {
                return currency_format($row->initial_balance, $row->currency);
            })
            ->addColumn('total', function ($row) {
                return $row->getAmountDisplay(false);
            })
            ->addColumn('total_charge', function ($row) {
                $operation = '+';
                if (in_array($row->type, ['Withdrawal', 'Exchange'])) {
                    $operation = '-';
                }
                return $operation . currency_format($row->charge, $row->currency);
            })
            ->addColumn('prefix', function ($row) {
                if ($row->added) {
                    return '+';
                }
                return '-';
            })
            ->addColumn('grand_total', function ($row) {
                return $row->getTotal();
            })
            ->addColumn('details', function ($row) {
                $details = [];
                if ($row->type === 'Transfer') {
                    $details[] = [
                        'label' => __('Type'),
                        'value' => __('Wallet Transfer')
                    ];
                    $details[] = [
                        'label' => 'Total',
                        'value' => currency_format(
                            $row->amount + $row->charge,
                            $row->currency
                        )
                    ];
                }

                if ($row->type === 'Exchange') {
                    $details[] = [
                        'label' => __('Type'),
                        'value' => __('Wallet Exchange')
                    ];
                    $details[] = [
                        'label' => 'Total',
                        'value' => currency_format(
                            $row->amount - $row->charge,
                            $row->currency
                        )
                    ];
                }

                if ($row->type === 'Deposit') {
                    $details[] = [
                        'label' => __('Type'),
                        'value' => __('Deposit')
                    ];
                    $details[] = [
                        'label' => __('Payable'),
                        'value' => currency_format(
                            $row->amount + $row->charge,
                            $row->currency
                        )
                    ];
                }

                if ($row->type === 'Withdrawal') {
                    $details[] = [
                        'label' => __('Type'),
                        'value' => __('Withdrawal')
                    ];
                    $details[] = [
                        'label' => __('Receivable'),
                        'value' => currency_format(
                            $row->amount - $row->charge,
                            $row->currency
                        )
                    ];
                }

                if ($row->type === 'Subscription') {
                    $details[] = [
                        'label' => __('Type'),
                        'value' => __('Subscription')
                    ];
                    $details[] = [
                        'label' => __('Payable'),
                        'value' => currency_format(
                            $row->amount,
                            $row->currency
                        )
                    ];
                }

                if (!count($details)) {
                    $details[] = [
                        'label' => __('Total'),
                        'value' => currency_format(
                            $row->amount + $row->charge,
                            $row->currency
                        )
                    ];
                }

                $details[] = [
                    'label' => __('Charge'),
                    'value' => currency_format(
                        $row->charge,
                        $row->currency
                    )
                ];

                return $details;
            })
            ->editColumn('user.email', function($row) {
                if(env('APP_DEMO')) {
                    return protected_data($row->user->email, 'email');
                }
                return $row->user->email;
            })
            ->order(function ($query) {
                $query->orderBy('id', 'desc');
            });
    }
}
