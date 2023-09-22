<?php

namespace Modules\Cashier\Services\Clients\Stripe\Service;

use Modules\Cashier\Services\Clients\Stripe\StripeClient;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Create;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Delete;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Update;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Retrieve;

class InvoiceService extends StripeClient
{
    use Create, Retrieve, Delete, Update;

    protected $resourceName = 'invoices';

    public function upcoming($payload)
    {
        $resource = $this->resourceName;

        try {
            $result = $this->client->{$resource}->upcoming($payload)->toArray();

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            dd($e);
            report($e);
            return null;
        }
    }
}
