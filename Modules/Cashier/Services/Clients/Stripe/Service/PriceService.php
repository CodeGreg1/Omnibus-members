<?php

namespace Modules\Cashier\Services\Clients\Stripe\Service;

use Modules\Cashier\Services\Clients\Stripe\StripeClient;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Create;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Delete;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Update;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Retrieve;

class PriceService extends StripeClient
{
    use Create, Retrieve, Delete, Update;

    protected $resourceName = 'prices';
}