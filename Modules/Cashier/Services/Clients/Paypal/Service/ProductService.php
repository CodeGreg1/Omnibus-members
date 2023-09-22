<?php

namespace Modules\Cashier\Services\Clients\Paypal\Service;

use Modules\Cashier\Services\Clients\Paypal\PaypalClient;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\All;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Create;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Delete;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Retrieve;

class ProductService extends PaypalClient
{
    use Create, Retrieve, Delete, All;

    protected $resouceName = 'product';
}