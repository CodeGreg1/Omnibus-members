<?php

namespace Modules\Carts\Services\Clients;

class CheckoutService
{
    /**
     * @var CheckoutModeServiceFactory
     */
    private $modeServiceFactory;

    public function __get($name)
    {
        if (null === $this->modeServiceFactory) {
            $this->modeServiceFactory = new CheckoutModeServiceFactory();
        }

        return $this->modeServiceFactory->__get($name);
    }
}