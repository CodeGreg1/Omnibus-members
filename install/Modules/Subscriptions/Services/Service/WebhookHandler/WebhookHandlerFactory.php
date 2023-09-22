<?php

namespace Modules\Subscriptions\Services\Service\WebhookHandler;

use Modules\Subscriptions\Services\Service\AbstractServiceFactory;
use Modules\Subscriptions\Services\Service\WebhookHandler\Clients\Mollie;
use Modules\Subscriptions\Services\Service\WebhookHandler\Clients\Paypal;
use Modules\Subscriptions\Services\Service\WebhookHandler\Clients\Stripe;
use Modules\Subscriptions\Services\Service\WebhookHandler\Clients\Razorpay;

class WebhookHandlerFactory extends AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'paypal' => Paypal::class,
        'stripe' => Stripe::class,
        'mollie' => Mollie::class,
        'razorpay' => Razorpay::class,
    ];

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}