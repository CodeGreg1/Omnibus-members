<?php

namespace Modules\Carts\Exceptions;

use Exception;

class CheckoutSessionException extends Exception
{
    /**
     * Throw a not items execption
     *
     * @return static
     */
    public static function checkoutNotFound()
    {
        return new static(__('Checkout not found'));
    }

    /**
     * Throw a not items execption
     *
     * @return static
     */
    public static function noItems()
    {
        return new static(__('No items to checkout'));
    }

    /**
     * Throw an invalid item
     *
     * @return static
     */
    public static function invalidItem($id)
    {
        return new static(__('Item:id, not a puchasable item.', ['id' => $id]));
    }

    /**
     * Throw an unprocessable items
     *
     * @return static
     */
    public static function unprocessableItems()
    {
        return new static(__('Items on checkout are unprocessable.'));
    }

    /**
     * Throw an invalid payload
     *
     * @return static
     */
    public static function unprocessableAttribute($message)
    {
        return new static($message);
    }

    /**
     * Throw a out of stock
     *
     * @return static
     */
    public static function outOfStock($item)
    {
        return new static(__('Item :name is out of stock.', ['name' => $item->cartItemName()]));
    }

    public static function unprocessedCheckout()
    {
        return new static(__('Checkout session is not processed yet.'));
    }
}