<?php

namespace Modules\Carts\Services;

use Illuminate\Foundation\Application;

/**
 * @mixin Cart
 */
class CartManager
{
    protected Application $app;
    protected array $instances      = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function instance(?string $name = null): CartInterface
    {
        $name = $name ?: $this->getDefaultInstance();

        return $this->instances[$name] = $this->get($name);
    }

    public function set(string $name, $instance): self
    {
        $this->instances[$name] = $instance;

        return $this;
    }

    public function getDefaultInstance(): string
    {
        return $this->app['config']['carts.default'] ?? 'cart';
    }

    public function __call($method, $parameters)
    {
        return $this->instance()->$method(...$parameters);
    }

    protected function get(string $name)
    {
        $class = $this->app['config']['carts.classes.cart'];

        return $this->instances[$name] ?? new $class($this->resolve($name), $name);
    }

    protected function resolve($name)
    {
        $config  = $this->getConfig($name);
        $storage = $config['storage'] ?? $this->app['config']['carts.storage.default'] ?? 'session';

        return $this->app['cart.storage']->store($storage, $name);
    }

    protected function getConfig(string $name): array
    {
        return $this->app['config']["carts.instances.{$name}"] ?: [];
    }
}