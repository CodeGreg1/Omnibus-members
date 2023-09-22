<?php

namespace Modules\Carts\Storage;

use Illuminate\Support\Manager;

class StorageManager extends Manager
{
    public function store(?string $name = null): StorageInterface
    {
        return $this->driver($name);
    }

    public function getDefaultDriver(): string
    {
        return $this->config['ecommerce.storage.default'] ?? 'session';
    }

    public function createSessionDriver(): SessionStorage
    {
        $config = $this->getConfig('session');

        return new SessionStorage($config);
    }

    protected function getConfig(string $storeName): array
    {
        return $this->config['ecommerce.storage.stores.' . $storeName] ?? [];
    }
}