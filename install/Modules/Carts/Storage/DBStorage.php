<?php

namespace Modules\Carts\Storage;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Carts\Helpers\Helpers;
use Modules\Carts\Services\CartItem;
use Modules\Carts\Services\ItemAttributeCollection;
use Modules\Carts\Services\ItemConditionCollection;

class DBStorage implements StorageInterface
{
    /**
     * items repository instance
     *
     * @var CartItem
     */
    protected $items;

    /**
     * Cart key identifier for specific user
     */
    protected $cartKey;

    /**
     * the cache storage constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->modelClasses = $config['modelClasses'];

        $this->cartKey = $config['cart_key'] ?? 'id';
        $this->fallbackStorage = storageManager()->store($config['fallback'] ?? 'session');
    }

    /**
     * add value to storage
     *
     * @param string|int $key
     * @param $item
     *
     * @return $item
     */
    public function add($key, $item)
    {
        $modelKey = $this->getKey($key, 'model');
        $sessionId = $this->getKey($key);
        if (!$this->hasModelFor($modelKey)) {
            return $this->fallbackStorage->add($key, $item);
        }

        return $this->modelFor($modelKey)->addEloquentModel($sessionId, $item);
    }

    /**
     * update value from storage
     *
     * @param string|int $key
     * @param string|int $id
     * @param $item
     *
     * @return $item
     */
    public function update($key, $id, $item)
    {
        $modelKey = $this->getKey($key, 'model');
        $sessionId = $this->getKey($key);
        if (!$this->hasModelFor($modelKey)) {
            return $this->fallbackStorage->update($key, $id, $item);
        }

        return $this->modelFor($modelKey)->updateEloquentModel($sessionId, $id, $item);
    }

    /**
     * get value from storage
     *
     * @param string|int $key
     * @param string|int $id
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $id, $default = null)
    {
        $modelKey = $this->getKey($key, 'model');
        $sessionId = $this->getKey($key);
        if (!$this->hasModelFor($modelKey)) {
            return $this->fallbackStorage->get($key, $id, $default);
        }

        return $this->modelFor($modelKey)->findEloquentModel($sessionId, $id, $default);
    }

    /**
     * check if item exists in cache
     *
     * @param string|int $key
     * @param string|int $id
     *
     * @return bool
     */
    public function has($key, $id): bool
    {
        $modelKey = $this->getKey($key, 'model');
        $sessionId = $this->getKey($key);
        if (!$this->hasModelFor($modelKey)) {
            return $this->fallbackStorage->has($key, $id);
        }

        return $this->modelFor($modelKey)->hasEloquentModel($sessionId, $id);
    }

    /**
     * get all items from storage
     *
     * @param string|int $key
     * @param mixed|null $default
     *
     * @return Collection
     */
    public function list($key, $params = [], $default = [])
    {
        $modelKey = $this->getKey($key, 'model');
        $sessionId = $this->getKey($key);
        if (!$this->hasModelFor($modelKey)) {
            return $this->fallbackStorage->list($key, $default);
        }

        return $this->modelFor($modelKey)->getEloquentModelList($sessionId, $params, $default);
    }

    /**
     * remove item from storage
     *
     * @param string|int $key
     * @param string|int|array $id
     *
     * @return self
     */
    public function remove($key, $id): StorageInterface
    {
        $modelKey = $this->getKey($key, 'model');
        $sessionId = $this->getKey($key);
        if (!$this->hasModelFor($modelKey)) {
            return $this->fallbackStorage->remove($key, $id);
        }

        if (!is_array($id)) {
            $id = [$id];
        }

        $this->modelFor($modelKey)->removeEloquentModel($sessionId, $id);

        return $this;
    }

    /**
     * clear cart from cache
     *
     * @param string|int $key
     *
     * @return self
     */
    public function clear($key): StorageInterface
    {
        $modelKey = $this->getKey($key, 'model');
        $sessionId = $this->getKey($key);
        if (!$this->hasModelFor($modelKey)) {
            return $this->fallbackStorage->clear($key);
        }

        $this->modelFor($modelKey)->clearEloquentModelList($sessionId);

        return $this;
    }

    /**
     * count total number of items
     *
     * @param string|int $key
     *
     * @return int
     */
    public function count($key): int
    {
        $modelKey = $this->getKey($key, 'model');
        $sessionId = $this->getKey($key);
        if (!$this->hasModelFor($modelKey)) {
            return $this->fallbackStorage->clear($key);
        }

        return $this->modelFor($modelKey)->countEloquentModelList($sessionId);
    }

    protected function getKey($key, $value = 'id')
    {
        $values = explode("::", $key);
        if ($value === 'id') {
            return $values[0];
        }

        return $values[1] ?? $values[0];;
    }

    protected function hasModelFor(string $key): bool
    {
        return $this->modelClassFor($key) !== null;
    }

    protected function modelClassFor(string $key): ?string
    {
        switch ($key) {
            case 'cart_items':
                return $this->modelClasses['cart_items'] ?? null;

            case 'cart_conditions':
                return $this->modelClasses['cart_conditions'] ?? null;
        }

        return null;
    }

    protected function modelFor(string $key)
    {
        $class = $this->modelClassFor($key);
        return (new $class);
    }
}