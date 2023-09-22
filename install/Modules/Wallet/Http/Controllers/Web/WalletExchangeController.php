<?php

namespace Modules\Wallet\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Wallet\Events\WalletExchangeCompleted;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Wallet\Http\Requests\ProcessWalletExchangeRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class WalletExchangeController extends BaseController
{
    /**
     * @var AvailableCurrenciesRepository
     */
    public $currencies;

    public function __construct(
        AvailableCurrenciesRepository $currencies
    ) {
        parent::__construct();

        $this->currencies = $currencies;
    }

    /**
     * Process echange wallets.
     * @param ProcessWalletExchangeRequest $request
     * @return Renderable
     */
    public function process(ProcessWalletExchangeRequest $request)
    {
        $amount = floatval($request->get('amount'));
        $currency = $request->get('from_currency');
        $targetCurrency = $request->get('to_currency');
        $fixedCharge = floatval(currency_format(wallet_charge($currency), $currency, false));
        $rate = wallet_rate($currency);
        $currency_rate = [];
        $currency_rate[$targetCurrency] = floatval(currency(1, $currency, $targetCurrency, false));
        $currency_rate[$currency] = 1;

        $charge = $fixedCharge;
        $rateCharge = 0;
        if ($rate > 0) {
            $rateCharge = floatval(currency_format((($rate / 100) * $amount), $currency, false));
            $charge = $charge + $rateCharge;
        }

        $toAmount = floatval(number_precision(
            ($amount - $charge) * $currency_rate[$targetCurrency],
            2
        ));

        $request->session()->put('wallet_exchange_checkout', $data = [
            'approved' => setting('allow_otp_wallet_exchange', 'disable') === 'enable' ? 0 : 1,
            'from' => [
                'currency_name' => Currency::getCurrencyProp($currency, 'name'),
                'amount' => $amount,
                'currency' => $currency,
                'amount_display' => currency_format($amount, $currency)
            ],
            'to' => [
                'currency_name' => Currency::getCurrencyProp($targetCurrency, 'name'),
                'amount' => $toAmount,
                'currency' => $targetCurrency,
                'amount_display' => currency_format($toAmount, $targetCurrency),
                'reg_amount' => currency_format($amount * $currency_rate[$targetCurrency], $targetCurrency)
            ],
            'currency_rate' => $currency_rate,
            'fixed_charge' => $fixedCharge,
            'fixed_charge_display' => currency_format($fixedCharge, $currency),
            'rate' => $rate,
            'rate_charge' => $rateCharge,
            'rate_charge_display' => currency_format($rateCharge, $currency),
            'total_charge' => $charge,
            'total_charge_display' => currency_format($charge, $currency),
        ]);

        $this->validAmount($data);

        return $this->successResponse(__('Please click continue to checkout.'), $data);
    }

    public function checkout(Request $request)
    {
        $this->authorize('user.profile.wallet.send-money.checkout');

        $user = $request->user();
        $data = $request->session()->pull('wallet_exchange_checkout');
        if (!$data) {
            return $this->errorNotFound(__('Checkout data not found'));
        }

        if (!$data['approved']) {
            return $this->errorNotFound(__('Checkout data not found'));
        }

        $result = DB::transaction(
            function () use ($data, $user) {
                $fromWallet = $user->unLoadWallet(
                    $user->getWalletByCurrency($data['from']['currency']),
                    $data['from']['amount']
                );

                $toWallet = $user->addWallet(
                    $data['to']['currency'],
                    $data['to']['amount']
                );

                WalletExchangeCompleted::dispatch($fromWallet, $toWallet, [
                    'amount' => $data['from']['amount'],
                    'fixed_charge' => $data['fixed_charge'],
                    'percent_charge_rate' => $data['rate'],
                    'percent_charge' => $data['rate_charge'],
                    'charge' => $data['total_charge']
                ], [
                    'amount' => $data['to']['amount']
                ]);

                return $toWallet;
            },
            3
        );

        if (!$result) {
            return $this->errorNotFound(__('Something went wrong on wallet exchange process'));
        }

        return $this->successResponse(__('Successfully converted wallet.'), [
            'redirectTo' => route('user.profile.wallet.index')
        ]);
    }

    protected function validAmount($data)
    {
        $wallet = auth()->user()->getWalletByCurrency($data['from']['currency']);
        if (!$wallet) {
            return false;
        }

        if ($data['from']['amount'] > $wallet->balance) {
            return false;
        }

        return true;
    }
}
