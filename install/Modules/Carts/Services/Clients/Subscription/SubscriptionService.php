<?php

namespace Modules\Carts\Services\Clients\Subscription;

use Modules\Carts\Services\Clients\Subscription\SubscriptionServiceFactory;

class SubscriptionService
{
    /**
     * @var SubscriptionServiceFactory
     */
    private $subscriptionServiceFactory;

    public function __get($name)
    {
        if (null === $this->subscriptionServiceFactory) {
            $this->subscriptionServiceFactory = new SubscriptionServiceFactory();
        }

        return $this->subscriptionServiceFactory->__get($name);
    }
}