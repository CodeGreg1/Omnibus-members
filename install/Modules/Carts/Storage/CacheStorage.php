<?php

namespace Modules\Carts\Storage;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

class CacheStorage implements StorageInterface
{
    /**
     * cache repository instance
     *
     * @var Repository
     */
    protected Repository $cache;

    /**
     * the cache storage constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $driver = $config['driver'] ?? 'default';

        if ($driver === 'default') {
            $driver = null;
        }

        $this->cache  = Cache::store($driver);
    }

    /**
     * add value to cache
     *
     * @param string|int $key
     * @param $item
     *
     * @return $item
     */
    public function add($key, $item)
    {
        $cart = $this->list($key);
        $cart->push($item);
        $this->cache->set($key, $item);

        return $item;
    }

    /**
     * update value from cache
     *
     * @param string|int $key
     * @param string|int $id
     * @param $item
     *
     * @return $item
     */
    public function update($key, $id, $item)
    {
        $cart = $this->list($key);
        $cart = $cart->map(function ($cartItem) use ($id, $item) {
            if ($id === $cartItem->id) {
                return $item;
            }
            return $cartItem;
        });

        $this->cache->set($key, $item);

        return $item;
    }

    /**
     * get value from cache
     *
     * @param string|int $key
     * @param string|int $id
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $id, $default = null)
    {
        $cart = $this->list($key);

        return $cart->firstWhere(config('carts.storage.item_key'), $id) ?? $default;
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
        return !!$this->get($key, $id);
    }

    /**
     * get all items from cache
     *
     * @param string|int $key
     * @param mixed|null $default
     *
     * @return Collection
     */
    public function list($key, $params = [], $default = [])
    {
        $items = $this->cache->get($key, $default);
        if (count($params)) {
            if (isset($params['items']) && is_array($params['items'])) {
                return $items->where(function ($item) use ($params) {
                    return in_array($item->cart_item_id, $params['items']);
                });
            }
        }

        return $items;
    }

    /**
     * remove item from cache
     *
     * @param string|int $key
     * @param string|int|array $id
     *
     * @return self
     */
    public function remove($key, $id): StorageInterface
    {
        $cart = $this->list($key);

        if (!is_array($id)) {
            $id = [$id];
        }

        $filtered = $cart->whereNotIn(config('carts.storage.item_key'), $id);

        $this->cache->put($key, $filtered);

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
        $this->cache->forget($key);

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
        return $this->list($key)->count();
    }
}