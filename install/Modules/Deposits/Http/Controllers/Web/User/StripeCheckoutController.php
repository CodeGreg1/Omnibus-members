<?php

namespace Modules\Deposits\Http\Controllers\Web\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Modules\Deposits\Events\DepositCreated;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deposits\Events\DepositApproved;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Deposits\Http\Requests\ProcessAutomaticDepositCheckoutRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class StripeCheckoutController extends AutomaticCheckoutController
{
    public function process(ProcessAutomaticDepositCheckoutRequest $request)
    {
        $data = $this->getData('stripe', $request->all());
        $request->session()->put('deposit_payload', $data);
        $client = Cashier::getClient('stripe');

        $amount = $data['total'];
        if (!in_array($data['currency'], $client->getConfig('zero_decimal_currencies'))) {
            $amount = amount_to_cents($data['total']);
        }

        $payload = [
            'success_url' => route('user.deposits.checkout.stripe.process-approve') . '?token={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('user.deposits.checkout.automatic', ['gateway' => 'stripe']),
            'payment_method_types' => $this->config['payment_methods'] ?? ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => Str::lower($data['currency']),
                        'product_data' => [
                            'name' => 'Order'
                        ],
                        'unit_amount' => $amount
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment'
        ];

        try {
            $response = $client->api()->checkout->getClient()
                ->checkout
                ->sessions
                ->create($payload);

            return $this->successResponse(
                __('Redirect to checkout.'),
                ['location' => $response->url]
            );
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function approve(Request $request)
    {
        $this->authorize('user.deposits.checkout.stripe.process-approve');

        $client = Cashier::getClient('stripe');
        $data = $request->session()->pull('deposit_payload');
        if ($data) {
            $response = $client->api()->checkout->retrieve($request->get('token'));

            if (
                $response
                && isset($response->payment_status)
                && $response->payment_status === 'paid'
            ) {
                $desposit = $this->deposits->create([
                    'user_id' => auth()->id(),
                    'gateway' => 'stripe',
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
