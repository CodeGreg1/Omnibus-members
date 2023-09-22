<?php

namespace Modules\Subscriptions\Services\Service\Price;

use Modules\Subscriptions\Services\Service\Price\Clients\Paypal;
use Modules\Subscriptions\Services\Service\Price\Clients\Stripe;
use Modules\Subscriptions\Services\Service\AbstractServiceFactory;
use Modules\Subscriptions\Services\Service\Price\Clients\Razorpay;

class PriceFactory extends AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'paypal' => Paypal::class,
        'stripe' => Stripe::class,
        'razorpay' => Razorpay::class,
    ];

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}
