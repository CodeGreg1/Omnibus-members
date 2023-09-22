<?php

namespace Modules\Transactions\Exports;

use Illuminate\Support\Carbon;
use Modules\Wallet\Models\Wallet;
use Illuminate\Contracts\View\View;
use Modules\Deposits\Models\Deposit;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Modules\Withdrawals\Models\Withdrawal;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Transactions\Models\Transaction;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExportPdf implements
    FromView,
    WithMapping,
    WithHeadings,
    WithStyles,
    WithDefaultStyles
{
    use Exportable;

    protected $input;

    // public function __construct(array $input)
    // {
    //     $this->input = $input;
    // }

    public function view(): View
    {
        return view('transactions::admin.exports.pdf', [
            'data' => $this->query()
        ]);
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
        )
            ->addColumn('date', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->addColumn('user', function ($row) {
                return $row->user->full_name ?? $row->user->email;
            })
            ->addColumn('total', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('total_charge', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('grand_total', function ($row) {
                return number_format($row->getTotal(false), 2);
            })
            ->toArray()['data'];
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
            number_format($transaction->amount, 2),
            number_format($transaction->charge, 2),
            number_format($transaction->getTotal(false), 2)
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        // Or return the styles array
        return [
            'fill' => [
                'fillType'   => Fill::FILL_NONE,
                'startColor' => ['argb' => Color::COLOR_DARKRED],
            ],
            'font' => [
                'size' => 12,
                'name' => 'Arial',
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getPageSetup()->setScale(100);

        $coordinates = $sheet->getCellCollection()->getCoordinates();

        $styles = [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'name' => 'Arial',
                ]
            ]
        ];
        // foreach ($coordinates as $cell) {
        //     $styles[$cell] = [

        //     ];
        // }
        // $sheet->getPageMargins()
        //     ->setLeft(0.1)
        //     ->setRight(0.1)
        //     ->setTop(0.1)
        //     ->setBottom(0.1)
        //     ->setHeader(0);
        return $styles;
    }
}
