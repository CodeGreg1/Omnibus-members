<?php

namespace Modules\Deposits\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Modules\Deposits\Events\DepositCreated;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deposits\Events\DepositApproved;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Exceptions\SignatureVerificationError;
use Modules\Deposits\Http\Requests\ProcessAutomaticDepositCheckoutRequest;
use Modules\Deposits\Http\Controllers\Web\User\AutomaticCheckoutController;

class RazorpayCheckoutController extends AutomaticCheckoutController
{
    public function process(ProcessAutomaticDepositCheckoutRequest $request)
    {
        $data = $this->getData('razorpay', $request->all());
        $request->session()->put('deposit_payload', $data);
        $client = Cashier::getClient('razorpay');

        $payload = [
            'amount' => amount_to_cents($data['total']),
            'currency' => $data['currency'],
            'description' => 'Charge payment',
            'customer' => [
                'name' => $request->user()->full_name,
                'email' => $request->user()->email
            ],
            'callback_url' => route('user.deposits.checkout.razorpay.process-approve'),
            'callback_method' => 'get'
        ];

        try {
            $response = $client->api()->payments->create($payload);

            if ($response && isset($response->short_url)) {
                return $this->successResponse(
                    __('Redirect to checkout.'),
                    ['location' => $response->short_url]
                );
            }

            return null;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function approve(Request $request)
    {
        $client = Cashier::getClient('razorpay');
        $data = $request->session()->pull('deposit_payload');
        if ($data) {
            if ($this->verifyPaymentSignature($request->all())) {
                if ($request->get('razorpay_payment_link_status') === 'paid') {
                    $desposit = $this->deposits->create([
                        'user_id' => auth()->id(),
                        'gateway' => 'razorpay',
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
        }

        return redirect(route('user.deposits.histories.index'));
    }

    protected function verifyPaymentSignature($attributes)
    {
        $actualSignature = $attributes['razorpay_signature'];

        $paymentId = $attributes['razorpay_payment_id'];

        if (isset($attributes['razorpay_order_id']) === true) {
            $orderId = $attributes['razorpay_order_id'];

            $payload = $orderId . '|' . $paymentId;
        } else if (isset($attributes['razorpay_subscription_id']) === true) {
            $subscriptionId = $attributes['razorpay_subscription_id'];

            $payload = $paymentId . '|' . $subscriptionId;
        } else if (isset($attributes['razorpay_payment_link_id']) === true) {
            $paymentLinkId     = $attributes['razorpay_payment_link_id'];

            $paymentLinkRefId  = $attributes['razorpay_payment_link_reference_id'];

            $paymentLinkStatus = $attributes['razorpay_payment_link_status'];

            $payload = $paymentLinkId . '|' . $paymentLinkRefId . '|' . $paymentLinkStatus . '|' . $paymentId;
        } else {
            throw new SignatureVerificationError(
                'Either razorpay_order_id, razorpay_subscription_id, or razorpay_payment_link_id must be present.'
            );
        }

        $client = Cashier::getClient('razorpay');
        $secret = $client->getConfig('api_secret');

        return $this->verifySignature($payload, $actualSignature, $secret);
    }

    protected function verifySignature($payload, $actualSignature, $secret)
    {
        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        // Use lang's built-in hash_equals if exists to mitigate timing attacks
        if (function_exists('hash_equals')) {
            $verified = hash_equals($expectedSignature, $actualSignature);
        } else {
            $verified = $this->hashEquals($expectedSignature, $actualSignature);
        }

        if ($verified === false) {
            throw new SignatureVerificationError(
                'Invalid signature passed'
            );
        }

        return true;
    }

    private function hashEquals($expectedSignature, $actualSignature)
    {
        if (strlen($expectedSignature) === strlen($actualSignature)) {
            $res = $expectedSignature ^ $actualSignature;
            $return = 0;

            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $return |= ord($res[$i]);
            }

            return ($return === 0);
        }

        return false;
    }
}
