<?php

namespace Modules\Wallet\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\Wallet\Events\SendMoneyCompleted;
use Modules\Users\Repositories\UserRepository;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Wallet\Http\Requests\ProcessWalletSendMoneyRequest;

class WalletSendMoneyController extends BaseController
{
    /**
     * @var UserRepository
     */
    public $users;

    public function __construct(UserRepository $users)
    {
        parent::__construct();

        $this->users = $users;
    }

    /**
     * Process wallet send money.
     * @param ProcessWalletSendMoneyRequest $request
     * @return Renderable
     */
    public function process(ProcessWalletSendMoneyRequest $request)
    {
        $request->session()->put('wallet_send_money_checkout', $data = [
            'approved' => setting('allow_otp_send_money', 'disable') === 'enable' ? 0 : 1,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'amount_display' => currency_format($request->amount, $request->currency),
            'fixed_charge' => $request->fixedCharge,
            'fixed_charge_display' => currency_format($request->fixedCharge, $request->currency),
            'rate' => $request->rate,
            'rate_charge' => $request->rateCharge,
            'rate_charge_display' => currency_format($request->rateCharge, $request->currency),
            'total_charge' => $request->charge,
            'total_charge_display' => currency_format($request->charge, $request->currency),
            'total' => $request->total,
            'total_display' => currency_format($request->total, $request->currency),
            'recepient' => $this->users->where('email', $request->get('email'))->first()
        ]);

        return $this->successResponse(__('Please click continue to checkout.'), $data);
    }

    public function checkout(Request $request)
    {
        $this->authorize('user.profile.wallet.send-money.checkout');

        $user = $request->user();
        $data = $request->session()->pull('wallet_send_money_checkout');
        if (!$data) {
            return $this->errorNotFound(__('Checkout data not found'));
        }

        if (!$data['approved']) {
            return $this->errorNotFound(__('Checkout data not found'));
        }

        $result = DB::transaction(function () use ($user, $data) {
            $senderWallet = $user->unLoadWallet(
                $user->getWalletByCurrency($data['currency']),
                $data['total']
            );

            $receiverWallet = $data['recepient']->addWallet(
                $data['currency'],
                $data['amount']
            );

            SendMoneyCompleted::dispatch(
                $senderWallet,
                $receiverWallet,
                array_merge(
                    [
                        'sender_balance' => $senderWallet->balance + $data['total'],
                        'receiver_balance' => $receiverWallet->balance - $data['amount']
                    ],
                    $data
                )
            );

            return true;
        }, 3);

        if (!$result) {
            return $this->errorNotFound(__('Something went wrong on sending money.'));
        }

        return $this->successResponse(__('Successfully sent money.'), [
            'redirectTo' => route('user.profile.wallet.index')
        ]);
    }
}
