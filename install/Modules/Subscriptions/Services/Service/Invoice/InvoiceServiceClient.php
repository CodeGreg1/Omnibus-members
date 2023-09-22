<?php

namespace Modules\Subscriptions\Services\Service\Invoice;

use Modules\Cashier\Facades\Cashier;

class InvoiceServiceClient
{
    protected $resourceName = 'invoices';

    protected $api;

    protected $gateway;

    public function __construct()
    {
        $this->api = Cashier::client($this->gateway);
    }
}