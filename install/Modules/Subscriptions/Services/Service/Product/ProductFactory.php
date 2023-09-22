<?php

namespace Modules\Subscriptions\Services\Service\Product;

use Modules\Subscriptions\Services\Service\AbstractServiceFactory;
use Modules\Subscriptions\Services\Service\Product\Clients\Paypal;
use Modules\Subscriptions\Services\Service\Product\Clients\Stripe;

class ProductFactory extends AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'paypal' => Paypal::class,
        'stripe' => Stripe::class
    ];

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}
