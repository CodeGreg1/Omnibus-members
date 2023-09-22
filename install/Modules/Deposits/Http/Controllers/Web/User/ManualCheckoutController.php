<?php

namespace Modules\Deposits\Http\Controllers\Web\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Deposits\Events\DepositCreated;
use Illuminate\Contracts\Support\Renderable;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Deposits\Repositories\DepositsRepository;
use Modules\Wallet\Repositories\ManualGatewaysRepository;
use Modules\Deposits\Http\Requests\ProcessManualDepositCheckoutRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class ManualCheckoutController extends BaseController
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
     * @var DepositsRepository
     */
    public $deposits;

    public function __construct(
        AvailableCurrenciesRepository $currencies,
        ManualGatewaysRepository $manualGateways,
        DepositsRepository $deposits
    ) {
        parent::__construct();

        $this->currencies = $currencies;
        $this->manualGateways = $manualGateways;
        $this->deposits = $deposits;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($methodId)
    {
        $this->authorize('user.deposits.checkout.manual');

        $gateway = $this->manualGateways->where([
            'id' => $methodId, 'type' => 'deposit'
        ])->firstOrFail();

        return view('deposits::user.checkout.manual', [
            'pageTitle' => config('deposits.name'),
            'gateway' => $gateway
        ]);
    }

    /**
     * Process checkout for manual gateways
     *
     * @param ProcessManualDepositCheckoutRequest $request
     * @param int $methodId
     * @return Renderable
     */
    public function process(ProcessManualDepositCheckoutRequest $request, $methodId)
    {
        $charge = $request->gateway->fixed_charge ?? 0;
        $percentCharge = 0;

        if ($request->gateway->percent_charge > 0) {
            $percentCharge = round((($request->gateway->percent_charge / 100) * $request->get('amount')), 2);
            $charge = $charge + $percentCharge;
        }

        $deposit = $this->deposits->create([
            'user_id' => auth()->id(),
            'trx' => (string) Str::uuid(),
            'method_id' => $request->gateway->id,
            'amount' => $request->get('amount'),
            'fixed_charge' => $request->gateway->fixed_charge ?? 0,
            'percent_charge_rate' => $request->gateway->percent_charge ?? 0,
            'percent_charge' => $percentCharge,
            'charge' => $charge,
            'currency' => $request->gateway->currency,
            'status' => 0
        ]);

        if ($deposit) {
            $details = null;
            if ($request->gateway->user_data && count($request->gateway->user_data)) {
                $user_data = $request->gateway->user_data;
                foreach ($request->gateway->user_data as $key => $value) {
                    $value->value = '';
                    if ($value->field_type === 'image_upload') {
                        $image = $request->user_data[$value->field_name];
                        if ($image && $image->isValid()) {
                            $media = $deposit->addMedia($image)->toMediaCollection('image');
                            if ($media) {
                                $value->value = $media->getUrl();
                            }
                        }
                    } else {
                        $value->value = $request->user_data[$value->field_name] ?? '';
                    }

                    $user_data[$key] = $value;
                }
                $details = json_encode($user_data, JSON_UNESCAPED_SLASHES);
            }

            if ($details) {
                $deposit->details = $details;
                $deposit->save();
            }

            DepositCreated::dispatch($deposit);
        }

        if ($deposit) {
            return $this->successResponse(__('Redirect to deposit history.'), [
                'location' => route('user.deposits.histories.index')
            ]);
        }

        return $this->errorResponse(__('Something went wrong while processing the deposit.'));
    }
}
