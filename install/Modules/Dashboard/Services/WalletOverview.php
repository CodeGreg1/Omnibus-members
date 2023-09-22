<?php

namespace Modules\Dashboard\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\Transactions\Models\Transaction;

use Modules\AvailableCurrencies\Models\AvailableCurrency;

class WalletOverview
{
    public function getDepositOverview($query, $walletQuery)
    {
        $deposits = collect([]);
        $completed = (clone $query)
            ->where('status', 1)
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $pending = (clone $query)
            ->where('status', 0)
            ->whereNull('rejected_at')
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $rejected = (clone $query)
            ->where('status', 0)
            ->whereNotNull('rejected_at')
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $wallets = $walletQuery
            ->where('balance', '>', 0)
            ->select('currency', DB::raw('sum(balance) as total'))
            ->groupBy('currency')
            ->get()
            ->pluck('total', 'currency');

        // $currencies = collect([
        //     ...$wallets->keys(),
        //     ...$completed->keys(),
        //     ...$pending->keys(),
        //     ...$rejected->keys()
        // ])->unique();

        if (setting('allow_wallet_multi_currency') !== 'enable') {
            $currencies = AvailableCurrency::where('code', setting('currency'))->get()->pluck('code');
        } else {
            $currencies = AvailableCurrency::where('status', 1)->get()->pluck('code');
        }

        if (in_array(setting('currency'), $currencies->toArray())) {
            $currencies = $currencies->prepend(setting('currency'))->unique();
        }

        if (!$currencies->count()) {
            $defaultCurrency = setting('currency');
            return collect([$defaultCurrency => [
                [
                    'label' => __('Total Balance in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency),
                    'icon' => 'fas fa-wallet',
                    'color' => 'primary'
                ],
                [
                    'label' => __('Success Deposit in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency),
                    'icon' => 'fas fa-check',
                    'color' => 'success'
                ],
                [
                    'label' => __('Pending Deposit in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency),
                    'icon' => 'fas fa-clock',
                    'color' => 'warning'
                ],
                [
                    'label' => __('Rejected Deposit in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency),
                    'icon' => 'fas fa-ban',
                    'color' => 'danger'
                ]
            ]]);
        }

        foreach ($currencies as $currency) {
            $deposits->put($currency, [
                [
                    'label' => __('Total Balance in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($wallets->get($currency) ?? 0, $currency),
                    'icon' => 'fas fa-wallet',
                    'color' => 'primary'
                ],
                [
                    'label' => __('Success Deposit in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($completed->get($currency) ?? 0, $currency),
                    'icon' => 'fas fa-check',
                    'color' => 'success'
                ],
                [
                    'label' => __('Pending Deposit in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($pending->get($currency) ?? 0, $currency),
                    'icon' => 'fas fa-clock',
                    'color' => 'warning'
                ],
                [
                    'label' => __('Rejected Deposit in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($rejected->get($currency) ?? 0, $currency),
                    'icon' => 'fas fa-ban',
                    'color' => 'danger'
                ]
            ]);
        }

        return $deposits;
    }

    public function getWithdrawalOverview($query)
    {
        $withdrawals = collect([]);
        $completed = (clone $query)
            ->where('status', 1)
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $pending = (clone $query)
            ->where('status', 0)
            ->whereNull('rejected_at')
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $rejected = (clone $query)
            ->where('status', 0)
            ->whereNotNull('rejected_at')
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        // $currencies = collect([
        //     ...$completed->keys(),
        //     ...$pending->keys(),
        //     ...$rejected->keys()
        // ])->unique();

        if (setting('allow_wallet_multi_currency') !== 'enable') {
            $currencies = AvailableCurrency::where('code', setting('currency'))->get()->pluck('code');
        } else {
            $currencies = AvailableCurrency::where(
                'status',
                1
            )->get()->pluck('code');
        }

        if (in_array(setting('currency'), $currencies->toArray())) {
            $currencies = $currencies->prepend(setting('currency'))->unique();
        }

        if (!$currencies->count()) {
            $defaultCurrency = setting('currency');
            return collect([$defaultCurrency => [
                [
                    'label' => __('Approved Withdrawals in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency),
                    'icon' => 'fas fa-check',
                    'color' => 'success'
                ],
                [
                    'label' => __('Pending Withdrawals in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency),
                    'icon' => 'fas fa-clock',
                    'color' => 'warning'
                ],
                [
                    'label' => __('Rejected Withdrawals in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency),
                    'icon' => 'fas fa-ban',
                    'color' => 'danger'
                ]
            ]]);
        }

        foreach ($currencies as $currency) {
            $withdrawals->put($currency, [
                [
                    'label' => __('Approved Withdrawals in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($completed->get($currency) ?? 0, $currency),
                    'icon' => 'fas fa-check',
                    'color' => 'success'
                ],
                [
                    'label' => __('Pending Withdrawals in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($pending->get($currency) ?? 0, $currency),
                    'icon' => 'fas fa-clock',
                    'color' => 'warning'
                ],
                [
                    'label' => __('Rejected Withdrawals in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($rejected->get($currency) ?? 0, $currency),
                    'icon' => 'fas fa-ban',
                    'color' => 'danger'
                ]
            ]);
        }

        return $withdrawals;
    }

    protected function getLatestTransactions($user = null)
    {
        return Transaction::with(['transactionable', 'user'])
            ->when($user && $user instanceof User, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->limit(10)
            ->latest()
            ->get()
            ->map(function ($item) {
                $operation = '+';
                if (in_array($item->type, ['Withdrawal', 'Exchange'])) {
                    $operation = '-';
                }
                $charge = $operation . currency_format($item->charge, $item->currency);

                $type = class_basename(get_class($item->transactionable));
                $total = currency_format($item->amount, $item->currency);
                if ($type === 'Wallet') {
                    if (Str::contains($item->description, 'Send')) {
                        $total = currency_format(
                            $item->amount + $item->charge,
                            $item->currency
                        );
                    }
                    if (Str::contains($item->description, 'Received')) {
                        $total = currency_format(
                            $item->amount,
                            $item->currency
                        );
                    }
                    if (Str::contains($item->description, 'Conversion')) {
                        if (Str::contains($item->description, 'Subtracted')) {
                            $total = currency_format(
                                $item->amount - $item->charge,
                                $item->currency
                            );
                        }
                        if (Str::contains($item->description, 'Added')) {
                            $total = currency_format(
                                $item->amount,
                                $item->currency
                            );
                        }
                    }
                }

                if ($type === 'Withdrawal') {
                    $total = currency_format(
                        $item->amount - $item->charge,
                        $item->currency
                    );
                }

                if ($type === 'Deposit') {
                    $total = currency_format(
                        $item->amount + $item->charge,
                        $item->currency
                    );
                }

                return (object) [
                    'user' => $item->user->full_name ?? $item->user->email,
                    'date' => $item->created_at->toUserTimezone()->toUserFormat(),
                    'description' => $item->description,
                    'initial_balance' => currency_format($item->initial_balance, $item->currency),
                    'amount' => currency_format($item->amount, $item->currency),
                    'charge' => $charge,
                    'total' => $total
                ];
            });
    }
}
