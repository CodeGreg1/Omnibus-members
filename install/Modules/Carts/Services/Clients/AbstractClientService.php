<?php

namespace Modules\Carts\Services\Clients;

use Modules\Carts\Models\Checkout;
use Modules\Cashier\Facades\Cashier;

abstract class AbstractClientService
{
    protected $gateway;

    protected $client;

    /**
     * Initializes a new instance of the {@link AbstractClientService} class.
     *
     */
    public function __construct()
    {
        $this->client = Cashier::client($this->gateway);
    }

    abstract public function process(Checkout $checkout);

    abstract public function getToken(array $attributes);
}