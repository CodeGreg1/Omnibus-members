<?php

namespace Modules\Carts\Services;

use Illuminate\Contracts\Support\Arrayable;
use Modules\Carts\Contracts\PurchasableItem;
use Modules\Carts\Services\Session;

interface CartInterface extends Arrayable
{
    public function session(Session $session): CartInterface;

    public function instanceName(): string;

    public function get($itemId);

    public function has($itemId);

    public function add(
        $name,
        $price,
        $quantity,
        PurchasableItem $purchasable
    );

    public function items(): CartCollection;

    public function update($id, array $data);

    public function remove($id);

    public function clear();

    public function toArray();

    public function toJson($options = 0);
}