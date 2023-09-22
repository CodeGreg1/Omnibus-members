<?php

namespace Modules\Cashier\Services;

use Modules\Cashier\Facades\Cashier;

class ClientService
{
    /**
     * @var ClientServiceFactory
     */
    private $coreServiceFactory;

    public function __get($name)
    {
        if (null === $this->coreServiceFactory) {
            $this->coreServiceFactory = new ClientServiceFactory(Cashier::getClients());
        }

        return $this->coreServiceFactory->__get($name);
    }
}