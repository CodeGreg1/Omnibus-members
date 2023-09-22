<?php

namespace Modules\Orders\States\Tracking;

class InTransit extends TrackingState
{
    public static $name = 'in_transit';

    public function color(): string
    {
        return 'success';
    }

    public function toArray()
    {
        return static::$name;
    }
}