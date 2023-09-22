<?php

namespace Modules\Cashier\Services\Clients\Stripe\Service;

use Modules\Cashier\Services\Clients\Stripe\StripeClient;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\All;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Create;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Delete;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Retrieve;

class ProductService extends StripeClient
{
    use Create, Retrieve, Delete, All;

    protected $resourceName = 'products';
}