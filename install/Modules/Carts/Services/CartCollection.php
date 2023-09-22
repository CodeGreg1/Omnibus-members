<?php

namespace Modules\Carts\Services;

use Illuminate\Support\Collection;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Carts\Facades\Cart;
use Modules\Carts\Helpers\Helpers;

class CartCollection extends Collection
{
    /**
     * check if an item exists by item ID
     *
     * @param $itemId
     * @return bool
     */
    public function has($id)
    {
        return !!$this->firstWhere('cart_item_id', $id);
    }

    /**
     * get an item on a cart by item ID
     *
     * @param $itemId
     * @return CartItem
     */
    public function get($key, $default = null)
    {
        return $this->firstWhere('cart_item_id', $key) ?? value($default);
    }

    /**
     * Check if cart is shippable
     *
     * @return bool
     */
    public function shippable()
    {
        return $this->first()->purchasable->cartItemShippable();
    }

    /**
     * get cart collection sub total
     * @param bool $formatted
     * @return float
     */
    public function getSubtotal($formatted = true)
    {
        $sum = $this->sum(function (CartItem $item) {
            return $item->getTotalPriceWithConditions(false);
        });

        $conditions = Cart::getConditions()
            ->filter(function (ItemConditionCollection $cond) {
                return $cond->getTarget() === 'subtotal';
            });

        // if there is no conditions, lets just return the sum
        if (!$conditions->count()) {
            if (!$formatted) {
                return $sum;
            }

            return currency_format($sum, Currency::getUserCurrency());
        }

        // there are conditions, lets apply it
        $newTotal = 0.00;
        $process = 0;

        $conditions->each(function (ItemConditionCollection $cond) use ($sum, &$newTotal, &$process) {

            // if this is the first iteration, the toBeCalculated
            // should be the sum as initial point of value.
            $toBeCalculated = ($process > 0) ? $newTotal : $sum;

            $newTotal = $cond->applyCondition($toBeCalculated);

            $process++;
        });

        if (!$formatted) {
            return $newTotal;
        }

        return currency_format($newTotal, Currency::getUserCurrency());
    }

    /**
     * the new total in which conditions are already applied
     *
     * @return float
     */
    public function getTotal($formatted = true)
    {
        $total = $this->sum(function ($item) {
            return $item->getTotalPrice(false);
        });

        if (!$formatted) {
            return $total;
        }

        return currency_format(
            $total,
            Currency::getUserCurrency()
        );
    }
}