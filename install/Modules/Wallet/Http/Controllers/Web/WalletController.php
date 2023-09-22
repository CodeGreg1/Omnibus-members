<?php

namespace Modules\Wallet\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use function GuzzleHttp\Promise\settle;
use Modules\Wallet\Models\WalletEmailType;
use Modules\EmailTemplates\Services\Mailer;
use Illuminate\Contracts\Support\Renderable;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;

use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class WalletController extends BaseController
{
    /**
     * @var AvailableCurrenciesRepository
     */
    public $currencies;

    public $mailer;

    public function __construct(
        AvailableCurrenciesRepository $currencies
    ) {
        parent::__construct();

        $this->currencies = $currencies;
        $this->mailer = new Mailer;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $this->authorize('user.profile.wallet.index');

        $wallets = $this->getWallets();
        $activeWallets = $wallets->where('balance');

        return view('wallet::index', [
            'pageTitle' => __('Account wallet'),
            'defaultWallet' => $activeWallets->first() ?? $wallets->first(),
            'wallets' => $wallets,
            'allow_wallet_exchange_otp' => setting('allow_otp_wallet_exchange', 'disable') === 'enable' ? 1 : 0,
            'allow_send_money_otp' => setting('allow_otp_send_money', 'disable') === 'enable'  ? 1 : 0
        ]);
    }

    public function sendOtp(Request $request)
    {
        if (Cache::has($request->user()->id . '_otp')) {
            return $this->successResponse(__('Success'));
        }

        // user, otp, expires
        $otp = Cache::remember($request->user()->id . '_otp', 10800, function () {
            return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        });

        $this->mailer->template(WalletEmailType::OTP)
            ->to($request->user()->email)
            ->with([
                'user' => $request->user()->full_name,
                'otp' => $otp,
                'expires' => now()->toUserTimezone()->addHours(3)->calendar()
            ])
            ->send();

        return $this->successResponse(__('One-Time Password send.'));
    }

    public function resenOtp(Request $request)
    {
        $otp = Cache::remember($request->user()->id . '_otp', 10800, function () {
            return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        });

        $this->mailer->template(WalletEmailType::OTP)
            ->to($request->user()->email)
            ->with([
                'user' => $request->user()->full_name,
                'otp' => $otp,
                'expires' => now()->toUserTimezone()->addHours(3)->calendar()
            ])
            ->send();

        return $this->successResponse(__('One-Time Password send.'));
    }

    public function verifyOtp(Request $request)
    {
        if (!Cache::has($request->user()->id . '_otp')) {
            return $this->errorNotFound(__('OTP not found'));
        }

        if ($request->get('otp') != Cache::get($request->user()->id . '_otp')) {
            return $this->errorNotFound(__('OTP not found'));
        }

        if ($request->has('otp_checkout_resource')) {
            $approval = $request->session()->get($request->get('otp_checkout_resource'));
            if ($approval) {
                $approval['approved'] = 1;
                $request->session()->put($request->get('otp_checkout_resource'), $approval);
            }
        }

        Cache::forget($request->user()->id . '_otp');

        return $this->successResponse(__('Otp verified.'));
    }

    protected function getWallets()
    {
        $user = auth()->user();
        if (setting('allow_wallet_multi_currency') === 'enable') {
            $wallets = $user->wallets;
            $userWallet = $wallets->first(function ($item) use ($user) {
                return $item->currency === $user->currency;
            });

            $curWallets = $wallets->filter(function ($item) use ($user) {
                return $item->currency !== $user->currency;
            });

            if ($userWallet) {
                $curWallets->prepend($userWallet);
            }

            $currencies = $this->currencies->getActive()
                ->sortBy(function ($currency, $index) use ($curWallets) {
                    return !in_array($currency->code, $curWallets->pluck(['currency'])->toArray());
                });
        } else {
            $currencies = $this->currencies->where('code', setting('currency'))->get();
        }

        return $currencies->map(function ($item) use ($user) {
            $balance = 0;
            if ($curr = $user->wallets->first(function ($c) use ($item) {
                return $c->currency === $item->code;
            })) {
                $balance = $curr->balance;
            }

            $item->balance = floatval($balance);

            // wallet exchange charges
            $item->exhange_charge = [
                'fixed_charge' => wallet_charge($item->code),
                'fixed_charge_display' => currency_format(wallet_charge($item->code), $item->code),
                'fixed_charge_rate' => wallet_rate($item->code)
            ];

            // send money charges
            $item->send_charge = [
                'fixed_charge' => wallet_send_charge($item->code),
                'fixed_charge_display' => currency_format(wallet_send_charge($item->code), $item->code),
                'fixed_charge_rate' => wallet_send_rate($item->code)
            ];

            return $item;
        });
    }
}
