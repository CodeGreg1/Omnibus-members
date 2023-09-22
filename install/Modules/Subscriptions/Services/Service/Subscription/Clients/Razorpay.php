<?php

namespace Modules\Subscriptions\Services\Service\Subscription\Clients;

use Illuminate\Support\Str;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\Subscription;
use Modules\Carts\Exceptions\SignatureVerificationError;
use Modules\Subscriptions\Services\Service\Subscription\SubscriptionServiceClient;

class Razorpay extends SubscriptionServiceClient
{
    protected $gateway = 'razorpay';

    public function getCustomerConsentUrl(Subscription $subscription, PackagePrice $price)
    {
        if ($price->isRecurring()) {
            $planId = $price->getGatewayId($this->gateway);

            $payload = [
                'plan_id' => $planId,
                'total_count' => 1200,
                'quantity' => 1,
                'customer_notify' => 1,
                'notes' => [
                    'subscriptionId' => $subscription->id,
                    'priceId' => $price->id
                ]
            ];

            $response = $this->api->subscriptions->create($payload);
            if ($response && isset($response->id)) {
                return $response->short_url;
            }
        } else {
            $total = $price->getUnitPrice(false);

            $payload = [
                'amount' => amount_to_cents($total),
                'currency' => $price->currency,
                'description' => 'Lifetime subscription creation',
                'customer' => [
                    'name' => $subscription->subscribable->full_name,
                    'email' => $subscription->subscribable->email
                ],
                'notes' => [
                    'subscriptionId' => $subscription->id,
                    'priceId' => $price->id
                ],
                'callback_url' => route('user.subscriptions.change-package.complete', [
                    'subscription' => $subscription->id
                ]),
                'callback_method' => 'get'
            ];

            $response = $this->api->payments->create($payload);

            if ($response && isset($response->short_url)) {
                return $response->short_url;
            }
        }

        return null;
    }

    public function completeChangePrice(
        Subscription $subscription,
        PackagePrice $price,
        $attributes = []
    ) {
        if ($this->verifyPaymentSignature($attributes)) {
            $payLink = $this->api->payments->retrieve($attributes['razorpay_payment_link_id']);
            if ($payLink->status === 'paid') {
                return [
                    'ref_profile_id' => (string) Str::uuid(),
                    'name' => 'main',
                    'gateway' => $this->gateway,
                    'trial_ends_at' => null,
                    'starts_at' => now()->format('Y-m-d H:i:s'),
                    'ends_at' => null,
                    'meta' => json_encode($payLink),
                    'recurring' => 0,
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

        $secret = $this->api->service->getConfig('api_secret');

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
