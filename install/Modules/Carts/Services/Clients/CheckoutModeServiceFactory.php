<?php

namespace Modules\Carts\Services\Clients;

use Modules\Carts\Services\Clients\Order\OrderService;
use Modules\Carts\Services\Clients\Subscription\SubscriptionService;
use Modules\Carts\Services\Clients\SubscriptionOnetime\SubscriptionOnetimeService;

class CheckoutModeServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'order' => OrderService::class,
        'subscription' => SubscriptionService::class,
        'subscription_onetime' => SubscriptionOnetimeService::class
    ];

    /**
     * @param $config
     */
    public function __construct()
    {
        $this->services = [];
    }

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }

    /**
     * @param string $name
     *
     * @return null
     */
    public function __get($name)
    {
        $serviceClass = $this->getServiceClass($name);

        if (null !== $serviceClass) {
            if (!\array_key_exists($name, $this->services)) {
                $this->services[$name] = new $serviceClass();
            }

            return $this->services[$name];
        }

        \trigger_error('Undefined property: ' . static::class . '::$' . $name);

        return null;
    }
}