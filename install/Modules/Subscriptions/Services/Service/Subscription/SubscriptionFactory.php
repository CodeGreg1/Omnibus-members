<?php

namespace Modules\Subscriptions\Services\Service\Subscription;

use Modules\Subscriptions\Services\Service\AbstractServiceFactory;
use Modules\Subscriptions\Services\Service\Subscription\Clients\Mollie;
use Modules\Subscriptions\Services\Service\Subscription\Clients\Paypal;
use Modules\Subscriptions\Services\Service\Subscription\Clients\Stripe;
use Modules\Subscriptions\Services\Service\Subscription\Clients\Wallet;
use Modules\Subscriptions\Services\Service\Subscription\Clients\Razorpay;

class SubscriptionFactory extends AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'paypal' => Paypal::class,
        'stripe' => Stripe::class,
        'mollie' => Mollie::class,
        'razorpay' => Razorpay::class,
        'wallet' => Wallet::class
    ];

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}