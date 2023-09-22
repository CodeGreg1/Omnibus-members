<?php

namespace Modules\Subscriptions\Services\Service\Product\Clients;

use Modules\Subscriptions\Services\Service\ApiResources\Retrieve;
use Modules\Subscriptions\Services\Service\Product\ProductServiceClient;

class Paypal extends ProductServiceClient
{
    use Retrieve;

    protected $gateway = 'paypal';

    public function create($package)
    {
        $payload = [
            "name" => $package->name,
            "description" => $package->name,
            "type" => "SERVICE",
            "category" => "SOFTWARE"
        ];

        return $this->api->products->create($payload);
    }

    public function delete($id)
    {
        return json_decode(json_encode([
            'id' => $id
        ]), FALSE);
    }
}