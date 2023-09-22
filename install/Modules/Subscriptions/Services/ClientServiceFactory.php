<?php

namespace Modules\Subscriptions\Services;

use Illuminate\Support\Arr;

class ClientServiceFactory
{
    protected $services = [];

    /**
     * @param $client
     */
    public function __construct($services)
    {
        $this->services = $services;
    }

    protected function getService($name)
    {
        return Arr::first($this->services, function ($service) use ($name) {
            return $service->key === $name;
        });
    }

    /**
     * @param string $name
     *
     * @return null|AbstractService|AbstractServiceFactory
     */
    public function __get($name)
    {
        $service = $this->getService($name);

        if (null !== $service) {
            return new $service->service_class($service);
        }

        \trigger_error('Undefined property: ' . static::class . '::$' . $name);

        return null;
    }
}