<?php

namespace Modules\Subscriptions\Services\Service\Price\Clients;

use Illuminate\Support\Str;
use Modules\Subscriptions\Services\Service\ApiResources\Retrieve;
use Modules\Subscriptions\Services\Service\Price\PriceServiceClient;

class Stripe extends PriceServiceClient
{
    use Retrieve;

    protected $gateway = 'stripe';

    public function create($productId, $price)
    {
        $payload = [
            'unit_amount' => amount_to_cents($price->price),
            'currency' => Str::lower($price->currency),
            'product' => $productId,
            'active' => true,
            'recurring' => [
                'interval' => $price->term->interval,
                'interval_count' => $price->term->interval_count
            ]
        ];

        return $this->api->prices->create($payload);
    }

    public function delete($id)
    {
        return $this->api->prices->update($id, [
            'active' => false
        ]);
    }
}
