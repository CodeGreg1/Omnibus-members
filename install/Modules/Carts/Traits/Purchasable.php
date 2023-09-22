<?php

namespace Modules\Carts\Traits;

use Modules\Carts\Facades\Cart;
use Modules\Carts\Models\CartItem;
use Modules\Carts\Models\TaxRate;

trait Purchasable
{
    public function initializePurchasable()
    {
        $this->append(['purchasable_object', 'purchasable_key']);
    }

    public function getPurchasableObjectAttribute()
    {
        return get_class($this);
    }

    public function getPurchasableKeyAttribute()
    {
        return $this->getKey();
    }

    /**
     * Get the model's cart items.
     */
    public function purchasables()
    {
        return $this->morphMany(CartItem::class, 'purchasable');
    }

    /**
     * add purchasable to cart.
     *
     * @param int $quantity
     *
     * @return bool
     */
    public function addToCart($quantity = 1)
    {
        Cart::add(
            $this->cartItemName(),
            $this->cartPrice(),
            1,
            $this
        );

        return true;
    }

    /**
     * return item stock total
     *
     * @return int
     */
    public function cartStock()
    {
        return 0;
    }

    /**
     * check if item is stockable
     *
     * @return bool
     */
    public function isStockable()
    {
        return true;
    }
}