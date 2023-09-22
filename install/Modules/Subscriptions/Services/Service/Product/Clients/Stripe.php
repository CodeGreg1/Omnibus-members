<?php

namespace Modules\Subscriptions\Services\Service\Product\Clients;

use Modules\Subscriptions\Services\Service\ApiResources\Delete;
use Modules\Subscriptions\Services\Service\ApiResources\Retrieve;
use Modules\Subscriptions\Services\Service\Product\ProductServiceClient;

class Stripe extends ProductServiceClient
{
    use Retrieve, Delete;

    protected $gateway = 'stripe';

    public function create($package)
    {
        $payload = [
            'name' => $package->name
        ];

        return $this->api->products->create($payload);
    }
}