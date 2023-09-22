<?php

namespace Modules\Subscriptions\Services\Service\Price;

use Modules\Cashier\Facades\Cashier;

class PriceServiceClient
{
    protected $resourceName = 'prices';

    protected $api;

    protected $gateway;

    public function __construct()
    {
        $this->api = Cashier::client($this->gateway);
    }
}