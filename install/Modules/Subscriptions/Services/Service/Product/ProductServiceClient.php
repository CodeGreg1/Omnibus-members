<?php

namespace Modules\Subscriptions\Services\Service\Product;

use Modules\Cashier\Facades\Cashier;

class ProductServiceClient
{
    protected $resourceName = 'products';

    protected $api;

    protected $gateway;

    public function __construct()
    {
        $this->api = Cashier::client($this->gateway);
    }
}