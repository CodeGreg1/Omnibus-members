<?php

namespace Modules\Subscriptions\Services\Service\Price\Clients;

use Modules\Subscriptions\Services\Service\ApiResources\Retrieve;
use Modules\Subscriptions\Services\Service\Price\PriceServiceClient;

class Razorpay extends PriceServiceClient
{
    use Retrieve;

    protected $gateway = 'razorpay';

    public function create($price)
    {
        $period = '';
        $interval = $price->term->interval_count;
        if ($price->term->interval === 'day') {
            if ($price->term->interval_count === 7) {
                $period = 'weekly';
                $interval = 1;
            } else {
                $period = 'daily';
            }
        } else {
            $period = $price->term->interval . 'ly';
        }

        $payload = [
            'period' => $period,
            'interval' => $interval,
            'item' => [
                'name' => $price->package->name,
                'amount' => amount_to_cents($price->price),
                'currency' => $price->currency,
                'description' => $price->package->name
            ]
        ];

        return $this->api->prices->create($payload);
    }
}
