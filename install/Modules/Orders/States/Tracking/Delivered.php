<?php

namespace Modules\Orders\States\Tracking;

class Delivered extends TrackingState
{
    public static $name = 'delivered';

    public function color(): string
    {
        return 'primary';
    }

    public function toArray()
    {
        return static::$name;
    }
}