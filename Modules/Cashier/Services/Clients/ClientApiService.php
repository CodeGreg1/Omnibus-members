<?php

namespace Modules\Cashier\Services\Clients;

use Illuminate\Support\Str;
use Modules\Cashier\Services\Client;
use Modules\Cashier\Services\Clients\ClientApiServiceFactory;

class ClientApiService
{
    public $service;

    /**
     * @var array<string, string>
     */
    private static $classMap = [];

    public function __construct(Client $service)
    {
        $this->service = $service;
    }

    /**
     * @var ClientApiServiceFactory
     */
    private $apiServiceFactory;

    public function __get($name)
    {
        if (null === $this->apiServiceFactory) {
            $this->apiServiceFactory = new ClientApiServiceFactory($this->service);
        }

        return $this->apiServiceFactory->__get($name);
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

    public function isValid()
    {
        return true;
    }

    public function clearCacheCredentials()
    {
    }

    public function hasCredentials()
    {
        return true;
    }
}