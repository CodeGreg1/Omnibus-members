<?php

namespace Modules\Subscriptions\Services\Service\Subscription;

use Modules\Cashier\Facades\Cashier;

class SubscriptionServiceClient
{
    protected $resourceName = 'products';

    protected $api;

    protected $gateway;

    public function __construct()
    {
        $this->api = Cashier::client($this->gateway);
    }
}