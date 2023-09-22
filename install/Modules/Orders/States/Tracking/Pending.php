<?php

namespace Modules\Orders\States\Tracking;

class Pending extends TrackingState
{
    public static $name = 'pending';

    public function color(): string
    {
        return 'secondary';
    }

    public function toArray()
    {
        return static::$name;
    }
}