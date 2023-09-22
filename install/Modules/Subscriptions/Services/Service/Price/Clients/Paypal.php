<?php

namespace Modules\Subscriptions\Services\Service\Price\Clients;

use Illuminate\Support\Str;
use Modules\Subscriptions\Services\Service\ApiResources\Retrieve;
use Modules\Subscriptions\Services\Service\Price\PriceServiceClient;

class Paypal extends PriceServiceClient
{
    use Retrieve;

    protected $gateway = 'paypal';

    public function create($productId, $price)
    {
        $payload = [
            'product_id' => $productId,
            'name' => $price->package->name,
            'payment_preferences' => [
                'auto_bill_outstanding' => true,
            ],
            'billing_cycles' => []
        ];

        if ($price->trial_days_count) {
            $payload['billing_cycles'][] = [
                'frequency' => [
                    'interval_unit' => 'DAY',
                    'interval_count' => $price->trial_days_count
                ],
                'tenure_type' => 'TRIAL',
                'sequence' => 1,
                'total_cycles' => 1
            ];
        }

        $payload['billing_cycles'][] = [
            'pricing_scheme' => [
                'fixed_price' => [
                    'currency_code' => $price->currency,
                    'value' => number_format($price->price, 2, '.', '')
                ]
            ],
            'frequency' => [
                'interval_unit' => Str::upper($price->term->interval),
                'interval_count' => $price->term->interval_count
            ],
            'tenure_type' => 'REGULAR',
            'sequence' => count($payload['billing_cycles']) + 1,
            'total_cycles' => 0
        ];

        return $this->api->prices->create($payload);
    }

    public function delete($id)
    {
        return json_decode(json_encode([
            'id' => $id
        ]), FALSE);
    }
}
