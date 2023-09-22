<?php

namespace Modules\Carts\Services\Clients\SubscriptionOnetime;

use Illuminate\Support\Str;
use Modules\Carts\Models\Checkout;
use Modules\Carts\Exceptions\SignatureVerificationError;
use Modules\Carts\Services\Clients\AbstractClientService;

class Razorpay extends AbstractClientService
{
    protected $gateway = 'razorpay';

    public function process(Checkout $checkout)
    {
        $lineItem = $checkout->lineItems->first();
        $total = $checkout->getTotal(false, $lineItem->checkoutable->currency);

        $payload = [
            'amount' => amount_to_cents($total),
            'currency' => $lineItem->checkoutable->currency,
            'description' => 'Lifetime subscription creation',
            'customer' => [
                'name' => $checkout->customer->full_name,
                'email' => $checkout->customer->email
            ],
            'notes' => [
                'checkoutId' => $checkout->id
            ],
            'callback_url' => route('user.pay.approval', [$checkout->id]),
            'callback_method' => 'get'
        ];

        $response = $this->client->payments->create($payload);

        if ($response && isset($response->short_url)) {
            $checkout->update([
                'metadata' => [
                    'paymentId' => $response->id
                ]
            ], (array) $checkout->getMetadata());

            return (object) [
                'url' => $response->short_url
            ];
        }

        return null;
    }

    public function store($data)
    {
        if ($this->verifyPaymentSignature($data)) {
            $payLink = $this->client->payments->retrieve($data['razorpay_payment_link_id']);
            if ($payLink->status === 'paid') {
                return [
                    'ref_profile_id' => (string) Str::uuid(),
                    'name' => 'main',
                    'trial_ends_at' => null,
                    'starts_at' => now()->format('Y-m-d H:i:s'),
                    'ends_at' => null,
                    'meta' => json_encode($payLink),
                    'payment' => [
                        'transaction_id' => $payLink->id,
                        'currency' => $payLink->currency,
                        'total' => normalize_amount($payLink->amount)
                    ]
                ];
            }
        }

        return null;
    }

    public function getToken(array $attributes)
    {
        return $attributes;
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
                'Either razorpay_order_id or razorpay_subscription_id or razorpay_payment_link_id must be present.'
            );
        }

        $secret = $this->client->service->getConfig('api_secret');

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
