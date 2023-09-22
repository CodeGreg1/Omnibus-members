<?php

namespace Modules\Carts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Carts\Helpers\Helpers;
use Modules\Carts\Services\CartItem as CartItemService;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "cart_item_id",
        "session_id",
        "instance",
        "name",
        "price",
        "quantity"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float'
    ];

    protected $with = ['purchasable'];

    public function session()
    {
        return $this->belongsTo(config('carts.classes.session'), 'session_id', 'id');
    }

    /**
     * Get the parent purchasable model.
     */
    public function purchasable()
    {
        return $this->morphTo();
    }

    /**
     * Get the unit price of the item
     */
    public function getUnitPrice($formatted = true)
    {
        return currency(
            $this->price,
            $this->purchasable->cartCurrency(),
            Currency::getUserCurrency(),
            $formatted
        );
    }

    /**
     * check if item exists in cache
     *
     * @param string|int $sessionId
     * @param string|int $id
     *
     * @return bool
     */
    public function hasEloquentModel($sessionId, $id)
    {
        return $this->where([
            config('carts.storage.stores.db.cart_key') => $sessionId,
            config('carts.storage.item_key') => $id
        ])->exists();
    }

    /**
     * Get all cart items by session
     *
     * @param mixed $sessionId
     * @param mixed $default
     * @return Collection
     */
    public function getEloquentModelList($sessionId, $params = [])
    {
        $query = $this->where(config('carts.storage.stores.db.cart_key'), $sessionId);
        if (count($params)) {
            $query = $query->when(
                isset($params['items']) && is_array($params['items']),
                function ($q) use ($params) {
                    return $q->whereIn(config('carts.storage.item_key'), $params['items']);
                }
            )
                ->when(
                    isset($params['limit']) && is_array($params['limit']),
                    function ($q) use ($params) {
                        return $q->skip(0)->take($params['limit']);
                    }
                );
        }

        $cart = $query->get();

        return $cart->map(function ($item) {
            return $this->itemToCart($item);
        });
    }

    /**
     * Get item for cart display.
     *
     * @param mixed $sessionId
     * @param mixed $cartItemId
     * @param mixed $default
     * @return CartItemService
     */
    public function findEloquentModel($sessionId, $cartItemId, $default = null)
    {
        $item = $this->where([
            config('carts.storage.stores.db.cart_key') => $sessionId,
            config('carts.storage.item_key') => $cartItemId
        ])->first();

        if (!$item) {
            return $default;
        }

        return $this->itemToCart($item);
    }

    /**
     * remove item from storage
     *
     * @param string|int $sessionId
     * @param array $items
     *
     * @return self
     */
    public function removeEloquentModel($sessionId, $items)
    {
        $items = $this->where(config('carts.storage.stores.db.cart_key'), $sessionId)
            ->whereIn(config('carts.storage.item_key'), $items)->get();

        if ($items->count()) {
            $items->each(function ($item, $key) {
                $item->delete();
            });
        }
    }

    /**
     * clear cart from storage of session
     *
     * @param string|int $sessionId
     *
     * @return self
     */
    public function clearEloquentModelList($sessionId)
    {
        $this->where(config('carts.storage.stores.db.cart_key'), $sessionId)->delete();

        return $this;
    }

    /**
     * add cart item to storage
     *
     * @param string|int $sessionId
     * @param $item
     *
     * @return $item
     */
    public function addEloquentModel($sessionId, $item)
    {
        $attributes = Helpers::getFillableAttributes($this, $item->toArray());
        $attributes['session_id'] = $sessionId;
        return $item->purchasable->purchasables()->create($attributes);
    }

    /**
     * update cart item from storage
     *
     * @param string|int $sessionId
     * @param string|int $id
     * @param $item
     *
     * @return $item
     */
    public function updateEloquentModel($sessionId, $id, $item)
    {
        $attributes = Helpers::getFillableAttributes($this, $item->toArray());
        unset($attributes[config('carts.storage.item_key')]);
        $cartItem = $item->purchasable->purchasables()
            ->where(config('carts.storage.item_key'), $item->cart_item_id)->first();
        return tap($cartItem)->update($attributes);
    }

    /**
     * count total items from cart
     *
     * @param string|int $sessionId
     *
     * @return int
     */
    public function countEloquentModelList($sessionId)
    {
        return $this->where(config('carts.storage.stores.db.cart_key'), $sessionId)->count();
    }

    /**
     * format cart item to Cart item service instance
     *
     * @param CartItem
     * @return CartItemService
     */
    protected function itemToCart($item)
    {
        $cartItem = $item->toArray();
        $cartItem['purchasable'] = $item->purchasable;
        return new CartItemService($cartItem, config('carts'));
    }
}