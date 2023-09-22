<?php

namespace Modules\Carts\Services\Clients\Subscription;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Carts\Models\Checkout;
use Modules\Carts\Services\Clients\AbstractClientService;

class Mollie extends AbstractClientService
{
    protected $gateway = 'mollie';

    public function process(Checkout $checkout)
    {
        $lineItem = $checkout->lineItems()->first();

        $total = $checkout->getTotal(
            false,
            $lineItem->checkoutable->currency
        );

        if (!$lineItem->checkoutable->trial_days_count) {
            $description = $lineItem->checkoutable->term->title . ' payment';

            $stratDate = now()->add(
                $lineItem->checkoutable->term->interval,
                $lineItem->checkoutable->term->interval_count
            )->format('Y-m-d');
        } else {
            $description = currency_format($total, $lineItem->checkoutable->currency) . ' ' . $lineItem->checkoutable->term->title . ' payment with ' . $lineItem->checkoutable->trial_days_count . ' ' . Str::plural(
                'day',
                $lineItem->checkoutable->trial_days_count
            ) . ' free trial';
            $stratDate = now()->add(
                'day',
                $lineItem->checkoutable->trial_days_count
            )->format('Y-m-d');
            $total = 0;
        }

        $customer = $this->client->customers->create([
            'name' => $checkout->customer->full_name,
            'email' => $checkout->customer->email,
        ]);

        if ($customer && isset($customer->id)) {
            $payload = [
                'amount' => [
                    'currency' => $lineItem->checkoutable->currency,
                    'value' => number_format($total, 2, '.', '')
                ],
                'customerId' => $customer->id,
                'sequenceType' => 'first',
                'description' => $description,
                'redirectUrl' => route('user.pay.approval', [$checkout->id]),
                'cancelUrl' => $checkout->cancel_url,
                'metadata' => [
                    'checkoutId' => $checkout->id
                ],
                'webhookUrl' => route('cashier.webhook.handle', $this->gateway)
            ];

            $response = $this->client->payments->create($payload);

            if ($response && isset($response->id)) {
                $checkout->update([
                    'metadata' => [
                        'paymentId' => $response->id,
                        'stratDate' => $stratDate
                    ]
                ], (array) $checkout->getMetadata());

                return (object) [
                    'url' => $response->_links->checkout->href
                ];
            }
        }

        return null;
    }

    public function store($checkout)
    {
        $lineItem = $checkout->lineItems()->first();
        $paymentId = $checkout->getMetadata('paymentId');
        $payment = $this->client->payments->retrieve($paymentId);

        if ($payment && isset($payment->status) && $payment->status === 'paid') {
            $stratDate = $checkout->getMetadata('stratDate');

            $total = $checkout->getTotal(
                false,
                $lineItem->checkoutable->currency
            );

            $interval = $lineItem->checkoutable->term->interval_count . ' ' . Str::plural(
                $lineItem->checkoutable->term->interval,
                $lineItem->checkoutable->term->interval_count
            );

            $payload = [
                'amount' => [
                    'currency' => $lineItem->checkoutable->currency,
                    'value' => number_format($total, 2, '.', '')
                ],
                'interval' => $interval,
                'startDate' => $stratDate,
                'description' => $lineItem->checkoutable->term->title . ' payment (' . now()->format('Y-m-d H:i:s') . ')',
                'metadata' => [
                    'checkoutId' => $checkout->id
                ],
                'webhookUrl' => route('cashier.webhook.handle', $this->gateway)
            ];

            $response = $this->client->subscriptions->create($payment->customerId, $payload);
            if ($response && isset($response->id)) {
                $trial_ends_at = null;
                $ends_at = Carbon::create($response->nextPaymentDate)
                    ->format('Y-m-d') . ' ' . now()->format('H:i:s');
                if ($payment->amount->value == '0.00') {
                    $trial_ends_at = $ends_at;
                }

                $payload = [
                    'ref_profile_id' => $response->id,
                    'name' => 'main',
                    'trial_ends_at' => $trial_ends_at,
                    'starts_at' => now()->format('Y-m-d H:i:s'),
                    'ends_at' => $ends_at,
                    'meta' => json_encode($response),
                    'payment' => [
                        'transaction_id' => $payment->id,
                        'currency' => $payment->amount->currency,
                        'total' => $payment->amount->value
                    ]
                ];

                return $payload;
            }
        }

        return null;
    }

    public function getToken(array $attributes)
    {
        return $attributes['checkout'];
    }
}
