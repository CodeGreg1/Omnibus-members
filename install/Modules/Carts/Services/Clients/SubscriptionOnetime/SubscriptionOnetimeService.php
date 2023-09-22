<?php

namespace Modules\Carts\Services\Clients\SubscriptionOnetime;

use Modules\Carts\Services\Clients\SubscriptionOnetime\SubscriptionServiceFactory;

class SubscriptionOnetimeService
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