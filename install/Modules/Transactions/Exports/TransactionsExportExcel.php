<?php

namespace Modules\Transactions\Exports;

use Illuminate\Support\Carbon;
use Modules\Wallet\Models\Wallet;
use Modules\Deposits\Models\Deposit;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Modules\Withdrawals\Models\Withdrawal;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Transactions\Models\Transaction;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExportExcel implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function query()
    {
        return DataTables::eloquent(
            Transaction::withoutGlobalScope('currency')
                ->when(request('type') ?? false, function ($q, $type) {
                    if ($type === 'Deposit') {
                        return $q->whereHasMorph(
                            'transactionable',
                            [Deposit::class, Wallet::class]
                        )->where('description', 'LIKE', 'Deposited%');
                    } else if ($type === 'Withdrawal') {
                        return $q->whereHasMorph(
                            'transactionable',
                            [Withdrawal::class, Wallet::class]
                        )->where('description', 'LIKE', 'Withdraw%');
                    } else if ($type === 'Transfer') {
                        return $q->whereHasMorph(
                            'transactionable',
                            [Wallet::class]
                        )->where('description', 'LIKE', 'Send%')
                            ->orWhere('description', 'LIKE', 'Received%');
                    } else if ($type === 'Exchange') {
                        return $q->whereHasMorph(
                            'transactionable',
                            [Wallet::class]
                        )->where('description', 'LIKE', 'Subtracted')
                            ->orWhere('description', 'LIKE', 'Added');
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
                ->when(request('date') ?? false, function ($query, $date) {
                    $range = collect(explode(',', $date))->map(function ($d) {
                        return Carbon::create($d);
                    })->toArray();

                    return $query->whereBetween('created_at', $range);
                })
                ->with(['transactionable', 'user'])
        )->getQuery();
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Date',
            'User',
            'Type',
            'Description',
            'Currency',
            'Amount',
            'Charge',
            'Grand Total'
        ];
    }

    /**
     * @var Transaction $transaction
     */
    public function map($transaction): array
    {
        return [
            $transaction->trx,
            $transaction->created_at->toUserTimezone()->toUserFormat(),
            $transaction->user->full_name ?? $transaction->user->email,
            $transaction->type,
            $transaction->description,
            $transaction->currency,
            $transaction->amount,
            $transaction->charge,
            $transaction->getTotal(false)
        ];
    }
}
