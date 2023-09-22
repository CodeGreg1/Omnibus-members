<?php

namespace Modules\Deposits\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Modules\Deposits\Events\DepositCreated;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deposits\Events\DepositApproved;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Deposits\Http\Requests\ProcessAutomaticDepositCheckoutRequest;

class MollieCheckoutController extends AutomaticCheckoutController
{
    public function process(ProcessAutomaticDepositCheckoutRequest $request)
    {
        $data = $this->getData('mollie', $request->all());
        $request->session()->put('deposit_payload', $data);
        $client = Cashier::getClient('mollie');

        $payload = [
            'description' => 'Charge payment',
            'redirectUrl' => route('user.deposits.checkout.mollie.process-approve'),
            'cancelUrl' => route('user.deposits.checkout.automatic', ['gateway' => 'mollie']),
            'amount' => [
                'currency' => $data['currency'],
                'value' => $data['total']
            ]
        ];

        try {
            $response = $client->api()->payments->create($payload);

            if ($response && isset($response->id)) {
                $client->api()->payments->update($response->id, [
                    'redirectUrl' => $payload['redirectUrl'] . '?id=' . $response->id
                ]);

                return $this->successResponse(
                    __('Redirect to checkout.'),
                    ['location' => $response->_links->checkout->href]
                );
            }

            return null;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function approve(Request $request)
    {
        $client = Cashier::getClient('mollie');
        $data = $request->session()->pull('deposit_payload');
        if ($data) {
            $response = $client->api()->payments->retrieve($request->get('id'));
            if (
                $response
                && isset($response->status)
                && $response->status === 'paid'
            ) {
                $desposit = $this->deposits->create([
                    'user_id' => auth()->id(),
                    'gateway' => 'mollie',
                    'amount' => $data['amount'],
                    'fixed_charge' => $data['fixed_charge'],
                    'percent_charge_rate' => $data['percent_charge_rate'],
                    'percent_charge' => $data['percent_charge'],
                    'charge' => $data['charge'],
                    'currency' => $data['currency'],
                    'status' => 1
                ]);

                DepositCreated::dispatch($desposit);
                DepositApproved::dispatch($desposit);
            }
        }

        return redirect(route('user.deposits.histories.index'));
    }
}
