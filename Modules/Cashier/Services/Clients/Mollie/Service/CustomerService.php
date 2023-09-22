<?php

namespace Modules\Cashier\Services\Clients\Mollie\Service;

use Modules\Cashier\Services\Clients\Mollie\MollieClient;
use Modules\Cashier\Services\Clients\Mollie\ApiResources\All;
use Modules\Cashier\Services\Clients\Mollie\ApiResources\Create;
use Modules\Cashier\Services\Clients\Mollie\ApiResources\Retrieve;

class CustomerService extends MollieClient
{
    use All, Create, Retrieve;

    protected $resourceName = 'customers';
}
