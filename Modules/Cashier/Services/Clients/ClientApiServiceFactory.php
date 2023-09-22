<?php

namespace Modules\Cashier\Services\Clients;

use Illuminate\Support\Str;
use Modules\Cashier\Services\Client;

class ClientApiServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'products' => 'ProductService',
        'prices' => 'PriceService',
        'checkout' => 'CheckoutService',
        'subscriptions' => 'SubscriptionService',
        'webhooks' => 'WebhookService',
        'invoices' => 'InvoiceService',
        'customers' => 'CustomerService',
        'payments' => 'PaymentService',
        'orders' => 'OrderService'
    ];

    /**
     * @param $config
     */
    public function __construct(Client $service)
    {
        $this->service = $service;
        $this->services = [];
    }

    protected function getServiceClass($name)
    {
        $service = \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
        return $this->getClientServiceNamespace($name) . '\\' . $service;
    }

    protected function getClientServiceNamespace($name)
    {
        return $this->service->config['service_base_namespace']
            ? $this->service->config['service_base_namespace']
            : 'Modules\\Cashier\\Services\\Clients\\' . Str::title($name) . '\\Service';
    }

    /**
     * @param string $name
     */
    public function __get($name)
    {
        $serviceClass = $this->getServiceClass($name);

        if (null !== $serviceClass) {
            if (!\array_key_exists($name, $this->services)) {
                $this->services[$name] = new $serviceClass($this->service);
            }

            return $this->services[$name];
        }

        \trigger_error('Undefined property: ' . static::class . '::$' . $name);

        return null;
    }
}