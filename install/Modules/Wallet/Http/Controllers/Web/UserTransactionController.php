<?php

namespace Modules\Wallet\Http\Controllers\Web;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Wallet\Models\Wallet;
use Modules\Deposits\Models\Deposit;
use Yajra\DataTables\Facades\DataTables;
use Modules\Withdrawals\Models\Withdrawal;
use Illuminate\Contracts\Support\Renderable;
use Modules\Transactions\Models\Transaction;
use Modules\Base\Http\Controllers\Web\BaseController;

class UserTransactionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function datatable()
    {
        $this->authorize('user.profile.wallet.transactions.datatable');

        return DataTables::eloquent(
            Transaction::where('user_id', auth()->id())
                ->whereIn('transactionable_type', [
                    'Modules\Deposits\Models\Deposit',
                    'Modules\Withdrawals\Models\Withdrawal',
                    'Modules\Wallet\Models\Wallet',
                    'Modules\Subscriptions\Models\Subscription'
                ])
                ->when(
                    setting('allow_wallet_multi_currency') !== 'enable',
                    function ($query) {
                        return $query->where('currency', setting('currency'));
                    }
                )
                ->with(['transactionable'])
        )
            ->addColumn('date', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->addColumn('initial_balance', function ($row) {
                return currency_format($row->initial_balance, $row->currency);
            })
            ->addColumn('total', function ($row) {
                return currency_format($row->amount, $row->currency);
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
                $type = class_basename(get_class($row->transactionable));
                if ($type === 'Wallet') {
                    if (Str::contains($row->description, 'Send')) {
                        return currency_format(
                            $row->amount + $row->charge,
                            $row->currency
                        );
                    }
                    if (Str::contains($row->description, 'Received')) {
                        return currency_format(
                            $row->amount,
                            $row->currency
                        );
                    }
                    if (Str::contains($row->description, 'Conversion')) {
                        if (Str::contains($row->description, 'Subtracted')) {
                            return currency_format(
                                $row->amount - $row->charge,
                                $row->currency
                            );
                        }
                        if (Str::contains($row->description, 'Added')) {
                            return currency_format(
                                $row->amount,
                                $row->currency
                            );
                        }
                    }
                }

                if ($type === 'Withdrawal') {
                    return currency_format(
                        $row->amount - $row->charge,
                        $row->currency
                    );
                }

                if ($type === 'Deposit') {
                    return currency_format(
                        $row->amount + $row->charge,
                        $row->currency
                    );
                }

                return currency_format($row->amount, $row->currency);
            })
            ->addColumn('details', function ($row) {
                $details = [];
                if ($row->type === 'Transfer') {
                    $details[] = [
                        'label' => __('Type'),
                        'value' => __('Wallet Transfer')
                    ];
                    $details[] = [
                        'label' => __('Total'),
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
                        'label' => __('Total'),
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

                if ($row->type === 'Commission') {
                    $details[] = [
                        'label' => __('Type'),
                        'value' => __('Commission')
                    ];
                    $details[] = [
                        'label' => __('Total'),
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
            ->order(function ($query) {
                $query->orderBy('id', 'desc');
            })
            ->toJson();
    }
}
