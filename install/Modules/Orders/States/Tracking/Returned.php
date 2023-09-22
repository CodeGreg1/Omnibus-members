<?php

namespace Modules\Orders\States\Tracking;

class Returned extends TrackingState
{
    public static $name = 'returned';

    public function color(): string
    {
        return 'danger';
    }

    public function toArray()
    {
        return static::$name;
    }
}