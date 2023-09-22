<?php

namespace Modules\Carts\Contracts;

interface PurchasableItem
{
    public function cartPrice();

    public function cartItemName();

    public function cartImage();

    public function cartItemShippable();

    public function addToCart($quantity);

    public function showPath();

    public function purchasables();

    public function checkoutables();
}