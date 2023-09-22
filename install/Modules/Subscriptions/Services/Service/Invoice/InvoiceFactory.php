<?php

namespace Modules\Subscriptions\Services\Service\Invoice;

use Modules\Subscriptions\Services\Service\AbstractServiceFactory;
use Modules\Subscriptions\Services\Service\Invoice\Clients\Paypal;
use Modules\Subscriptions\Services\Service\Invoice\Clients\Stripe;

class InvoiceFactory extends AbstractServiceFactory
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
