<?php

namespace Modules\Carts\Traits;

use Modules\Carts\Models\CheckoutItem;

trait Checkoutable
{
    /**
     * Get the model's checkout items.
     */
    public function checkoutables()
    {
        return $this->morphMany(CheckoutItem::class, 'checkoutable');
    }
}