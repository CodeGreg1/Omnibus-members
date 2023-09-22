<?php

namespace Modules\Withdrawals\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Withdrawals\Events\WithdrawRequestCreated;
use Modules\Wallet\Repositories\ManualGatewaysRepository;
use Modules\Withdrawals\Repositories\WithdrawalsRepository;
use Modules\Withdrawals\Http\Requests\StoreWithdrawCheckoutRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class WithdrawCheckoutController extends BaseController
{
    /**
     * @var AvailableCurrenciesRepository
     */
    public $currencies;

    /**
     * @var ManualGatewaysRepository
     */
    public $manualGateways;

    /**
     * @var WithdrawalsRepository
     */
    public $withdraws;

    public function __construct(
        AvailableCurrenciesRepository $currencies,
        ManualGatewaysRepository $manualGateways,
        WithdrawalsRepository $withdraws
    ) {
        parent::__construct();

        $this->currencies = $currencies;
        $this->manualGateways = $manualGateways;
        $this->withdraws = $withdraws;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request, $methodId)
    {
        $this->authorize('user.withdrawals.checkout.create');

        if (!auth()->user()->hasFunds()) {
            return redirect(route('user.withdrawals.index'))->withErrors(['message' => __('Your wallet is empty. Use deposit to load your wallet.')]);
        }

        $method = $this->manualGateways->withdraw()->where('id', $methodId)->firstOrFail();

        $currencies = $this->currencies->getActive()->map(function ($currency) {
            $currency->wallet_balance = auth()->user()->getWalletByCurrency($currency->code);
            return $currency;
        })->filter(function ($currency) {
            return $currency->wallet_balance;
        });

        $initialCurrency = $currencies->first(function ($currency) use ($method) {
            return $currency->code === $method->currency;
        });

        if (!$initialCurrency) {
            return back()->withErrors(['msg' => __('Insufficient balance for :wallet wallet', ['wallet' => $method->currency])]);
        }

        return view('withdrawals::user.checkout', [
            'pageTitle' => __('Withdrawal'),
            'otpRequestId' => uniqid($request->user()->id . '-'),
            'method' => $method,
            'currencies' => $currencies,
            'initialBalance' => currency_format($initialCurrency->wallet_balance->balance, $initialCurrency->code),
            'otp_wothdrawal' => setting('allow_otp_wothdrawal', 'disable') === 'enable'  ? 1 : 0
        ]);
    }

    public function process(StoreWithdrawCheckoutRequest $request)
    {
        $charge = $request->withdrawMethod->fixed_charge ?? 0;
        $percentCharge = 0;

        if ($request->withdrawMethod->percent_charge > 0) {
            $percentCharge = ($request->withdrawMethod->percent_charge / 100) * $request->get('amount');
            $charge = $charge + $percentCharge;
        }

        $request->session()->put('withdraw_checkout', [
            'approved' => setting('allow_otp_wothdrawal', 'disable') === 'enable'  ? 0 : 1,
            'withdraw' => [
                'user_id' => auth()->id(),
                'method_id' => $request->withdrawMethod->id,
                'amount' => $request->get('amount'),
                'fixed_charge' => $request->withdrawMethod->fixed_charge ?? 0,
                'percent_charge_rate' => $request->withdrawMethod->percent_charge ?? 0,
                'percent_charge' => $percentCharge,
                'charge' => $charge,
                'currency' => $request->withdrawMethod->currency,
                'status' => 0
            ]
        ]);

        return $this->successResponse(__('Checkout created'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param int $methodId
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->authorize('user.withdrawals.checkout.store');

        $data = $request->session()->pull('withdraw_checkout');

        if (!$data) {
            return $this->errorNotFound(__('Checkout data not found'));
        }

        if (!$data['approved']) {
            return $this->errorNotFound(__('Checkout data not found'));
        }

        $withdrawMethod = $this->manualGateways->findOrFail($data['withdraw']['method_id']);
        $withdraw = $this->withdraws->create($data['withdraw']);

        if ($withdraw) {
            $details = null;
            if (
                $withdrawMethod->user_data
                && count($withdrawMethod->user_data)
                && $request->has('user_data')
            ) {
                $user_data = $withdrawMethod->user_data;
                foreach ($user_data as $key => $value) {
                    if (isset($request->user_data[$value->field_name])) {
                        $value->value = '';
                        if ($value->field_type === 'image_upload') {
                            $image = $request->user_data[$value->field_name];
                            if ($image && $image->isValid()) {
                                $media = $withdraw->addMedia($image)->toMediaCollection('image');
                                if ($media) {
                                    $value->value = $media->getUrl();
                                }
                            }
                        } else {
                            $value->value = $request->user_data[$value->field_name] ?? '';
                        }

                        $user_data[$key] = $value;
                    }
                }
                $details = json_encode($user_data, JSON_UNESCAPED_SLASHES);
            }

            if ($details) {
                $withdraw->details = $details;
                $withdraw->save();
            }

            WithdrawRequestCreated::dispatch($withdraw);

            return $this->successResponse(__('Withdraw request successfully created. Redirect to deposit history.'), [
                'location' => route('user.withdrawals.histories.index')
            ]);
        }

        return $this->errorResponse(__('Something went wrong on processing withdraw request.'));
    }
}
