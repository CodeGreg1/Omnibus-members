<?php

namespace Modules\Cashier\Services\Clients\Razorpay\Service;

use Modules\Cashier\Services\Clients\Razorpay\RazorpayClient;
use Modules\Cashier\Services\Clients\Razorpay\ApiResources\Create;

class OrderService extends RazorpayClient
{
    use Create;

    protected $resourceName = 'orders';
}
