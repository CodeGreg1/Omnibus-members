<?php

namespace Modules\Carts\Storage;

use Illuminate\Support\Facades\Cookie;
use Modules\Carts\Services\CartItem;
use Modules\Carts\Services\ItemAttributeCollection;
use Modules\Carts\Services\ItemConditionCollection;

class CookieStorage implements StorageInterface
{
    /**
     * cookie lifetime
     *
     * @var
     */
    protected $lifetime;

    /**
     * the cache storage constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->lifetime = $config['expires'] ?? 180;
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
        Cookie::queue(Cookie::make($key, json_encode($cart), $this->lifetime));

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

        Cookie::queue(Cookie::make($key, json_encode($cart), $this->lifetime));

        return $item;
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
        $cart = Cookie::get($key, $default);

        $items = collect($cart)->map(function ($item) {
            $data = json_decode(json_encode($item), true);
            $data['attributes'] = new ItemAttributeCollection($data['attributes']);
            $data['conditions'] = new ItemConditionCollection($data['conditions']);
            $data['purchasable'] = new $data['purchasable_type']($data['purchasable']);
            $data['purchasable']->id = $item->purchasable->id;
            return new CartItem($data, config('carts'));
        });

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

        return $cart->firstWhere(config('carts.storage.item_key'), $id);
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
        $cart = $this->list($key);

        return !!$cart->firstWhere(config('carts.storage.item_key'), $id);
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

        Cookie::queue(Cookie::make($key, json_encode($filtered), $this->lifetime));

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
        Cookie::forget($key);

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