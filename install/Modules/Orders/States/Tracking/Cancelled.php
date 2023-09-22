<?php

namespace Modules\Orders\States\Tracking;

class Cancelled extends TrackingState
{
    public static $name = 'cancelled';

    public function color(): string
    {
        return 'warning';
    }

    public function toArray()
    {
        return static::$name;
    }
}