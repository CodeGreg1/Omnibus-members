<?php

namespace Modules\Carts\Services\Clients\Order;

class OrderService
{
    /**
     * @var OrderServiceFactory
     */
    private $paymentServiceFactory;

    public function __get($name)
    {
        if (null === $this->paymentServiceFactory) {
            $this->paymentServiceFactory = new OrderServiceFactory();
        }

        return $this->paymentServiceFactory->__get($name);
    }
}