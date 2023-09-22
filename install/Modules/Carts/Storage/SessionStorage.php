<?php

namespace Modules\Carts\Storage;

use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;

class SessionStorage implements StorageInterface
{
    /**
     * session store instance
     *
     * @var Store
     */
    protected Store $session;

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

        $this->session = app()->make(SessionManager::class)->driver($driver);
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
        $this->session->put($key, $item);

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

        $this->session->put($key, $item);

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
        $cart = $this->session->get($key, $default);

        if (!$cart) {
            return collect([]);
        }

        if (count($params)) {
            if (isset($params['items']) && is_array($params['items'])) {
                return $cart->where(function ($item) use ($params) {
                    return in_array($item->cart_item_id, $params['items']);
                });
            }
        }

        return $cart;
    }

    /**
     * remove item from cache
     *
     * @param string|int $key
     * @param string|int|array $id
     *
     * @return self
     */
    public function remove($key, $id): self
    {
        $cart = $this->list($key);
        if (!is_array($id)) {
            $id = [$id];
        }

        $filtered = $cart->whereNotIn(config('carts.storage.item_key'), $id);

        $this->session->put($key, $filtered);

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
        $this->session->forget($key);

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