<?php

namespace Modules\Deposits\Http\Controllers\Web\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Modules\Deposits\Events\DepositCreated;
use Modules\Deposits\Events\DepositApproved;
use Modules\Deposits\Http\Requests\ProcessAutomaticDepositCheckoutRequest;

class PaypalCheckoutController extends AutomaticCheckoutController
{
    public function process(ProcessAutomaticDepositCheckoutRequest $request)
    {
        $data = $this->getData('paypal', $request->all());
        $request->session()->put('deposit_payload', $data);
        $client = Cashier::getClient('paypal');

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $data['currency'],
                        'value' => $data['total']
                    ]
                ]
            ],
            'application_context' => [
                'user_action' => 'CONTINUE',
                'return_url' => route('user.deposits.checkout.paypal.process-approve'),
                'cancel_url' => route('user.deposits.checkout.automatic', ['gateway' => 'paypal'])
            ],
        ];

        try {
            $response = $client->api()->checkout->getClient()
                ->createOrder($payload);
            if (isset($response['error'])) {
                return $this->errorResponse($response['error']['message']);
            }

            if (isset($response['status']) && $response['status'] === 'CREATED') {
                $url = collect($response['links'])->first(function ($link) {
                    return $link['rel'] === 'approve';
                });

                if ($url) {
                    return $this->successResponse(
                        __('Redirect to checkout.'),
                        ['location' => $url['href']]
                    );
                }
            }

            return $this->errorResponse(
                __('Something went wrong during processing.')
            );
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function approve(Request $request)
    {
        $this->authorize('user.deposits.checkout.paypal.process-approve');

        $client = Cashier::getClient('paypal');
        $data = $request->session()->pull('deposit_payload');
        $response = $client->api()->checkout->approve($request->get('token'));

        if (
            $data
            && $response
            && isset($response->status)
            && $response->status === 'COMPLETED'
        ) {
            $desposit = $this->deposits->create([
                'user_id' => auth()->id(),
                'gateway' => 'paypal',
                'amount' => $data['amount'],
                'charge' => $data['charge'],
                'fixed_charge' => $data['fixed_charge'],
                'percent_charge_rate' => $data['percent_charge_rate'],
                'percent_charge' => $data['percent_charge'],
                'currency' => $data['currency'],
                'status' => 1
            ]);

            DepositCreated::dispatch($desposit);
            DepositApproved::dispatch($desposit);
        }

        return redirect(route('user.deposits.histories.index'));
    }
}
