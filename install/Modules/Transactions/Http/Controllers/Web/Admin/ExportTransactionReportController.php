<?php

namespace Modules\Transactions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Wallet\Models\Wallet;
use Modules\Deposits\Models\Deposit;
use Yajra\DataTables\Facades\DataTables;
use Modules\Withdrawals\Models\Withdrawal;
use Modules\Transactions\Models\Transaction;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Transactions\Exports\TransactionsExportPdf;
use Modules\Transactions\Exports\TransactionsExportExcel;

class ExportTransactionReportController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function excel(Request $request)
    {
        $this->authorize('admin.transactions.reports.exports.excel');

        $request = $request->merge([
            'start' => 0,
            'limit' => -1
        ]);

        return (new TransactionsExportExcel($request->all()))->download(
            'transactions.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function pdf(Request $request)
    {
        $this->authorize('admin.transactions.reports.exports.pdf');

        $request = $request->merge([
            'start' => 0,
            'limit' => -1
        ]);

        $data = $this->datatable($request)
            ->addColumn('user', function ($row) {
                return $row->user->full_name ?? $row->user->email;
            })
            ->toArray()['data'];

        $pdf = app('dompdf.wrapper')->loadView('transactions::admin.exports.pdf', [
            'data' => $data
        ]);

        return $pdf->download('tansactions.pdf');
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
                        )
                            ->where('description', 'LIKE', 'Send%')
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
                        return $q->whereHasMorph(
                            'transactionable',
                            [Deposit::class, Withdrawal::class, Wallet::class]
                        );
                    }
                }, function ($q) {
                    return $q->whereHasMorph(
                        'transactionable',
                        [Deposit::class, Withdrawal::class, Wallet::class]
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
            ->addColumn('grand_total', function ($row) {
                return $row->getTotal();
            })
            ->order(function ($query) {
                $query->orderBy('id', 'desc');
            });
    }
}
