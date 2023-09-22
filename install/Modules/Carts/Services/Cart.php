<?php

namespace Modules\Carts\Services;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Carts\Contracts\PurchasableItem;
use Modules\Carts\Exceptions\InvalidItemConditionException;
use Modules\Carts\Helpers\Helpers;
use Modules\Carts\Services\ConditionCollection;
use Modules\Carts\Storage\StorageInterface;
use Modules\Cashier\Facades\Cashier;

/**
 * Class Cart.
 *
 * used to create and manages carts(add item, update, remove, get all items).
 */
class Cart implements CartInterface, Jsonable
{
    /**
     * the session that owns the cart
     *
     * @var Session
     */
    protected Session $session;

    /**
     * the event dispatcher
     *
     * @var
     */
    protected $events;

    /**
     * the item storage
     *
     * @var StorageInterface
     */
    protected StorageInterface $storage;

    /**
     * the cart instance name
     *
     * @var
     */
    protected string $instanceName;

    /**
     * This holds the currently added item id in cart
     *
     * @var
     */
    protected $currentItemId;

    /**
     * the cart constructor
     *
     * @param Session $session
     * @param StorageInterface $storage
     * @param $events
     * @param string $instanceName
     */
    public function __construct(
        Session $session,
        StorageInterface $storage,
        $events,
        string $instanceName = 'cart'
    ) {
        $this->instanceName = $instanceName;

        $this->events = $events;
        $this->session = $session;
        $this->storage = $storage;

        $this->currentItem = null;
    }

    /**
     * sets the session
     *
     * this binds the cart to specific user.
     * used before all other methods.
     *
     * @param Session $session the session user
     * @return $this|bool
     */
    public function session(Session $session): CartInterface
    {
        $this->session = $session;

        return $this;
    }

    /**
     * get session owner of the cart
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * get the instance name of the car.
     *
     * @return string
     */
    public function instanceName(): string
    {
        return $this->instanceName;
    }

    /**
     * get an item on a cart by item ID
     *
     * @param $itemId
     * @return CartItem
     */
    public function get($itemId)
    {
        return $this->storage->get($this->session->getCartItemsKey(), $itemId);
    }

    /**
     * check if an item exists by item ID
     *
     * @param $itemId
     * @return bool
     */
    public function has($itemId)
    {
        return $this->storage->has($this->session->getCartItemsKey(), $itemId);
    }

    /**
     * add item to the cart, it can be an array or multi dimensional array
     *
     * @param string|array $name
     * @param float $price
     * @param int $quantity
     * @param PurchasableItem $purchasable
     * @param array $attributes
     * @param array $conditions
     * @return $this
     */
    public function add(
        $name,
        $price,
        $quantity,
        PurchasableItem $purchasable
    ) {
        if (is_array($name)) {
            if (Helpers::isMultiArray($name)) {
                foreach ($name as $item) {
                    $this->add(
                        $item['name'],
                        $item['price'],
                        $item['quantity'],
                        $item['purchasable']
                    );
                }
            } else {
                $this->add(
                    $name['name'],
                    $name['price'],
                    $name['quantity'],
                    $name['purchasable']
                );
            }

            return $this;
        }

        $data = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'instance' => $this->instanceName
        ];

        if ($this->session->model) {
            $data['user_id'] = $this->session->model->id;
        }

        $item = new CartItem(array_merge($data, [
            'purchasable' => $purchasable
        ]), config('carts'));

        // get the cart
        $cart = $this->items();

        if ($cart->has($item->cart_item_id)) {
            $this->update($item->cart_item_id, $data);
        } else {
            $this->addItem($item);
        }

        $this->currentItemId = $item->cart_item_id;

        return $this;
    }

    /**
     * Get cart's all contents from storage
     *
     * @return CartCollection
     */
    public function items($params = []): CartCollection
    {
        return (new CartCollection($this->storage->list($this->session->getCartItemsKey(), $params)))->reject(function ($item) {
            return !($item instanceof CartItem);
        });
    }

    /**
     * update a cart from storage
     *
     * @param $id (the item ID)
     * @param array $data
     * @return CartItem
     *
     * the $data will be an array, you don't need to pass all the data, only the key value
     * of the item you want to update on it
     */
    public function update($id, array $data)
    {
        if ($this->fireEvent('updating', $data) === false) {
            return false;
        }

        $cart = $this->items();

        $item = $cart->get($id);

        foreach ($data as $key => $value) {
            if ($key == 'quantity') {
                if ($value !== '-1') {
                    $item->validateQuantity();
                }

                $item = $this->updateQuantity($item, $key, $value);
            } else {
                $item[$key] = $value;
            }
        }

        $this->storage->update($this->session->getCartItemsKey(), $id, $item);

        $this->fireEvent('updated', $item);

        return $item;
    }

    public function count()
    {
        if ($this->fireEvent('counting') === false) {
            return false;
        }

        $count = $this->storage->count($this->session->getCartItemsKey());

        $this->fireEvent('counted');
        return $count;
    }

    /**
     * add item to cart collection
     *
     * @param CartItem $item
     * @return bool
     */
    protected function addItem(CartItem $item)
    {
        $item->validateQuantity();

        if ($this->fireEvent('adding', $item) === false) {
            return false;
        }

        $this->storage->add($this->session->getCartItemsKey(), $item);

        $this->fireEvent('added', $item);

        return true;
    }

    protected function checkStock($item)
    {
    }

    /**
     * removes an item on cart by item ID
     *
     * @param $id
     * @return bool
     */
    public function remove($id)
    {
        if ($this->fireEvent('removing', $id) === false) {
            return false;
        }

        $this->storage->remove($this->session->getCartItemsKey(), $id);

        $this->fireEvent('removed', $id);
        return true;
    }

    /**
     * clear cart
     *
     * @return bool
     */
    public function clear()
    {
        if ($this->fireEvent('clearing') === false) {
            return false;
        }

        $this->storage->clear($this->session->getCartItemsKey());

        $this->fireEvent('cleared');
        return true;
    }

    /**
     * update a cart item quantity relative to its current quantity
     *
     * @param $item
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function updateQuantity($item, $key, $value)
    {
        if (preg_match('/\-/', $value) == 1) {
            $value = (int)str_replace('-', '', $value);
            if (($item[$key] - $value) > 0) {
                $item[$key] -= $value;
            }
        } elseif (preg_match('/\+/', $value) == 1) {
            $item[$key] += (int)str_replace('+', '', $value);
        } elseif (preg_match('/\=/', $value) == 1) {
            $item[$key] = (int)str_replace('=', '', $value);
        } else {
            $item[$key] += (int)$value;
        }

        return $item;
    }

    /**
     * get cart sub total
     * @param bool $formatted
     * @return float
     */
    public function getSubTotal($formatted = true, $params = [])
    {
        $cart = $this->items($params);

        return $cart->getSubtotal($this, $formatted);
    }


    /**
     * the new total in which conditions are already applied
     *
     * @return float
     */
    public function getTotal($formatted = true, $params = [])
    {
        $cart = $this->items($params);

        return $cart->getTotal($formatted);
    }

    /**
     * get total quantity of items in the cart
     *
     * @return int
     */
    public function getTotalQuantity()
    {
        $items = $this->items();

        if ($items->isEmpty()) return 0;

        $count = $items->sum(function ($item) {
            return $item['quantity'];
        });

        return $count;
    }

    /**
     * Fire event
     *
     * @param $name
     * @param $value
     * @return mixed
     */
    protected function fireEvent($name, $value = [])
    {
        return $this->events->dispatch($this->instanceName() . '.' . $name, array_values([$value, $this]), true);
    }

    /**
     * Get the array representing the class
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'instance'  => $this->instanceName,
            'session'   => $this->session,
            'items'     => $this->items(),
            'currentItemId' => $this->currentItemId,
        ];
    }

    /**
     * Get the json representing the class
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}