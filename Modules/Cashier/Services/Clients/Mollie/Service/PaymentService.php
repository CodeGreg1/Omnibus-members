<?php

namespace Modules\Cashier\Services\Clients\Mollie\Service;

use Modules\Cashier\Services\Clients\Mollie\MollieClient;
use Modules\Cashier\Services\Clients\Mollie\ApiResources\All;
use Modules\Cashier\Services\Clients\Mollie\ApiResources\Create;
use Modules\Cashier\Services\Clients\Mollie\ApiResources\Update;
use Modules\Cashier\Services\Clients\Mollie\ApiResources\Retrieve;

class PaymentService extends MollieClient
{
    use Create, Retrieve, Update, All;

    protected $resourceName = 'payments';
}
