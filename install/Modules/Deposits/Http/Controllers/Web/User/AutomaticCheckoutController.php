<?php

namespace Modules\Deposits\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Deposits\Repositories\DepositsRepository;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;
use Modules\AvailableCurrencies\Http\Requests\AdminUpdateAvailableCurrencyRequest;

class AutomaticCheckoutController extends BaseController
{
    /**
     * @var AvailableCurrenciesRepository
     */
    public $currencies;

    /**
     * @var DepositsRepository
     */
    public $deposits;

    public function __construct(
        AvailableCurrenciesRepository $currencies,
        DepositsRepository $deposits
    ) {
        parent::__construct();

        $this->currencies = $currencies;
        $this->deposits = $deposits;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($gateway)
    {
        $this->authorize('user.deposits.checkout.automatic');

        $gateway = Cashier::getClient($gateway);
        $currencies = $this->currencies->getModel()
            ->when(setting('allow_wallet_multi_currency') === 'enable', function ($query) {
                return $query->where('status', 1);
            }, function ($query) {
                return $query->where('code', setting('currency'));
            })->get();

        return view('deposits::user.checkout.automatic', [
            'pageTitle' => config('deposits.name'),
            'currencies' => $currencies,
            'gateway' => $gateway,
            'min_limit' => setting($gateway->key . '_deposit_min_limit') ?? 0,
            'max_limit' => setting($gateway->key . '_deposit_max_limit') ?? 0,
            'currency_code' => setting('currency', config('cashier.currency')),
            'fixed_charge' => setting($gateway->key . '_deposit_fixed_charge') ?? 0,
            'fixed_charge_display' => currency_format(setting($gateway->key . '_deposit_fixed_charge', 0), setting('currency', config('cashier.currency'))),
            'percent_charge' => setting($gateway->key . '_deposit_percent_charge') ?? 0,
            'process_url' => route('user.deposits.checkout.' . $gateway->key . '.process')
        ]);
    }

    public function getData($gateway, $attributes)
    {
        $currency = $attributes['currency'];
        $amount = floatval(currency_format($attributes['amount'], $currency, false));
        $fixedCharge = floatval(currency_format(floatval(currency(
            floatval(setting($gateway . '_deposit_fixed_charge') ?? 0),
            setting('currency', config('cashier.currency')),
            $currency,
            false
        )), $currency, false));

        $charge = $fixedCharge;
        $percentCharge = 0;
        $percentChargeRate = floatval(setting($gateway . '_deposit_percent_charge') ?? 0);

        if ($percentChargeRate > 0) {
            $percentCharge = round((($percentChargeRate / 100) * $amount), 2);
            $charge = $charge + $percentCharge;
        }

        $charge = floatval(currency_format($charge, $currency, false));

        return [
            'amount' => $amount,
            'charge' => $charge,
            'fixed_charge' => $fixedCharge,
            'percent_charge_rate' => $percentChargeRate,
            'percent_charge' => $percentCharge,
            'currency' => $currency,
            'total' => currency_format(($amount + $charge), $currency, false)
        ];
    }
}
