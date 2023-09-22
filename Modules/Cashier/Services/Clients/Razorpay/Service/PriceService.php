<?php

namespace Modules\Cashier\Services\Clients\Razorpay\Service;

use Modules\Cashier\Services\Clients\Razorpay\RazorpayClient;
use Modules\Cashier\Services\Clients\Razorpay\ApiResources\All;
use Modules\Cashier\Services\Clients\Razorpay\ApiResources\Create;
use Modules\Cashier\Services\Clients\Razorpay\ApiResources\Retrieve;

class PriceService extends RazorpayClient
{
    use All, Retrieve, Create;

    protected $resourceName = 'plans';
}
