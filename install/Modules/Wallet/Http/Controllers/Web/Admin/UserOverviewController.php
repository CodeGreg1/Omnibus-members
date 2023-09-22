<?php

namespace Modules\Wallet\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Deposits\Repositories\DepositsRepository;
use Modules\AvailableCurrencies\Models\AvailableCurrency;

class UserOverviewController extends BaseController
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var DepositsRepository
     */
    protected $deposits;

    /**
     * @param UserRepository $users
     * @param DepositsRepository $deposits
     */
    public function __construct(
        UserRepository $users,
        DepositsRepository $deposits
    ) {
        parent::__construct();

        $this->users = $users;
        $this->deposits = $deposits;
    }

    /**
     * Display a listing of the resource.
     * @param int $id
     * @return Renderable
     */
    public function index($id)
    {
        if (setting('allow_wallet') !== 'enable') {
            return redirect(route('admin.users.show', $id));
        }

        $user = $this->users->find($id);

        return view('wallet::admin.user-overview', [
            'pageTitle' => __('Overview'),
            'user' => $user,
            'depositsOverview' => $this->getDepositOverview($user),
            'withdrawalsOverview' => $this->getWithdrawalOverview($user)
        ]);
    }

    public function getDepositOverview($user)
    {
        $deposits = collect([]);
        $completed = $user->deposits()
            ->where('status', 1)
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $pending = $user->deposits()
            ->where('status', 0)
            ->whereNull('rejected_at')
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $rejected = $user->deposits()
            ->where('status', 0)
            ->whereNotNull('rejected_at')
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        if (setting('allow_wallet_multi_currency') === 'enable') {
            $currencies = AvailableCurrency::where('status', 1)->get()->pluck('code');
        } else {
            $currencies = AvailableCurrency::where('code', setting('currency'))->get()->pluck('code');
        }

        // $currencies = collect([
        //     ...$completed->keys(),
        //     ...$pending->keys(),
        //     ...$rejected->keys()
        // ])->unique();

        if (in_array(setting('currency'), $currencies->toArray())) {
            $currencies = $currencies->prepend(setting('currency'))->unique();
        }

        if (!$currencies->count()) {
            $defaultCurrency = setting('currency');
            return collect([$defaultCurrency => [
                [
                    'label' => __('Success Deposit in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency)
                ],
                [
                    'label' => __('Pending Deposit in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency)
                ],
                [
                    'label' => __('Rejected Deposit in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency)
                ]
            ]]);
        }

        foreach ($currencies as $currency) {
            $deposits->put($currency, [
                [
                    'label' => __('Success Deposit in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($completed->get($currency) ?? 0, $currency)
                ],
                [
                    'label' => __('Pending Deposit in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($pending->get($currency) ?? 0, $currency)
                ],
                [
                    'label' => __('Rejected Deposit in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($rejected->get($currency) ?? 0, $currency)
                ]
            ]);
        }

        return $deposits;
    }

    public function getWithdrawalOverview($user)
    {
        $withdrawals = collect([]);
        $completed = $user->withdrawals()
            ->where('status', 1)
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $pending = $user->withdrawals()
            ->where('status', 0)
            ->whereNull('rejected_at')
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $rejected = $user->withdrawals()
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

        if (setting('allow_wallet_multi_currency') === 'enable') {
            $currencies = AvailableCurrency::where('status', 1)->get()->pluck('code');
        } else {
            $currencies = AvailableCurrency::where('code', setting('currency'))->get()->pluck('code');
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
                    'value' => currency_format(0, $defaultCurrency)
                ],
                [
                    'label' => __('Pending Withdrawals in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency)
                ],
                [
                    'label' => __('Rejected Withdrawals in :currency', [
                        'currency' => $defaultCurrency
                    ]),
                    'value' => currency_format(0, $defaultCurrency)
                ]
            ]]);
        }

        foreach ($currencies as $currency) {
            $withdrawals->put($currency, [
                [
                    'label' => __('Approved Withdrawals in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($completed->get($currency) ?? 0, $currency)
                ],
                [
                    'label' => __('Pending Withdrawals in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($pending->get($currency) ?? 0, $currency)
                ],
                [
                    'label' => __('Rejected Withdrawals in :currency', [
                        'currency' => $currency
                    ]),
                    'value' => currency_format($rejected->get($currency) ?? 0, $currency)
                ]
            ]);
        }

        return $withdrawals;
    }
}
