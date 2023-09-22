<?php

namespace Modules\Subscriptions\Services\Service\Subscription;

class Subscription
{
    /**
     * @var SubscriptionFactory
     */
    private $subscriptionFactory;

    /**
     * Constructor
     *
     * @param SubscriptionFactory $subscriptionFactory - (Dependency injection) If not provided, a SubscriptionFactory instance will be constructed.
     */
    public function __construct(SubscriptionFactory $subscriptionFactory = null)
    {
        if (null === $subscriptionFactory) {
            // Create the service factory
            $subscriptionFactory = new SubscriptionFactory();
        }
        $this->subscriptionFactory = $subscriptionFactory;
    }

    /**
     * @param  string $service
     *
     * @return \Userdesk\Subscription\Contracts\Service
     */

    public function provider($provider)
    {
        try {
            return $this->subscriptionFactory->{$provider};
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}