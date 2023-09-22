<?php

namespace Modules\Carts\Services;

use Cknow\Money\Money;
use Illuminate\Support\Collection;
use Modules\Carts\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Modules\Carts\Contracts\PurchasableItem;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Carts\Exceptions\InvalidItemException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Modules\Carts\Exceptions\CheckoutSessionException;

/**
 * Class CartItem
 *
 * the object representing an item from the cart.
 */
class CartItem extends Collection implements Arrayable
{
    /**
     * Sets the config parameters.
     *
     * @var
     */
    protected $config;

    /**
     * the cart item constructor
     *
     * @param array $parameters
     */
    public function __construct(array $parameters, $config = [])
    {
        parent::__construct($this->validate($parameters));

        if (!$this->has('cart_item_id')) {
            $this->put('cart_item_id', $this->getId());
        }

        $this->put('purchasable_id', $this->purchasable->id);
        $this->put('purchasable_type', $this->morphAlias($this->purchasable));

        $this->config = $config;
    }

    /**
     * get the unique identifier of the cart item.
     *
     * @return string
     */
    public function getId(): string
    {
        return sha1(implode("-", [
            $this->morphAlias($this->purchasable),
            $this->purchasable->getKey()
        ]));
    }

    /**
     * Get items pruchasable full namespace
     *
     * @return string
     */
    private function morphAlias(PurchasableItem $purchasable): string
    {
        $class = get_class($purchasable);
        foreach (Relation::$morphMap as $alias => $model) {
            if ($model === $class) {
                return $alias;
            }
        }

        return $class;
    }

    /**
     * validate Item data
     *
     * @param $item
     * @return array $item;
     * @throws InvalidItemException
     */
    protected function validate($item)
    {
        $rules = array(
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'regex:/^[\-\+\=]|[0-9]*$/'],
            'name' => ['required']
        );

        $validator = Validator::make($item, $rules);

        if ($validator->fails()) {
            throw new InvalidItemException($validator->messages()->first());
        }

        return $item;
    }

    /**
     * allows access to items of collection and the model attributes
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->has($name) || $name == 'model') {
            return !is_null($this->get($name)) ? $this->get($name) : $this->getPurchasableModel();
        }
        return null;
    }

    /**
     * get the unit price
     *
     * @return mixed|string|int|float
     */
    public function getUnitPrice($formatted = true)
    {
        if (!$formatted) {
            return $this->price;
        }

        return currency_format($this->price, $this->purchasable->currency);
    }

    /**
     * get the sum of price
     *
     * @return mixed|string|int|float
     */
    public function getTotalPrice($formatted = true)
    {
        $total = $this->price * $this->quantity;

        if ($formatted) {
            return currency_format($total, $this->purchasable->currency);
        }

        return $total;
    }

    public function validateQuantity()
    {
        if ($this->purchasable->isStockable()) {
            $stock = $this->purchasable->cartStock();
            if ($stock <= $this->quantity) {
                throw CheckoutSessionException::outOfStock($this->purchasable);
            }
        }

        return true;
    }

    /**
     * get original price()
     *
     * @return double|int
     */
    protected function getOriginalPrice()
    {
        return $this->price;
    }

    /**
     * return the associated model of an item
     *
     * @return bool
     */
    protected function getPurchasableModel()
    {
        if (!$this->purchasable) {
            return null;
        }

        $purchasable = $this->get('purchasable');

        return with(new $purchasable())->find($this->get('id'));
    }

    /**
     * return the array representation of the item
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }
}