<?php

namespace Modules\Subscriptions\Traits;

use Modules\Subscriptions\Models\Subscription;

trait Subscribable
{
    /**
     * Get the model's subscribables.
     */
    public function subscribables()
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }
}