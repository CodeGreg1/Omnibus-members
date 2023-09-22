<?php

namespace Modules\Orders\Traits;

use Modules\Orders\Models\OrderItem;

trait Orderable
{
    /**
     * Get the model's order items.
     */
    public function orderables()
    {
        return $this->morphMany(OrderItem::class, 'orderable');
    }
}