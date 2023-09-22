<?php

namespace Modules\Cashier\Services\Clients\Paypal\Service;

use Modules\Cashier\Services\Clients\Paypal\PaypalClient;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\All;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Create;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Delete;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Retrieve;

class PriceService extends PaypalClient
{
    use Create, Delete, Retrieve, All;

    protected $resouceName = 'plan';
}