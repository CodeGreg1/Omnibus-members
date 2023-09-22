<?php

namespace Modules\Blogs\States;

class Pending extends StatusState
{
    public static $name = 'pending';

    public function color(): string
    {
        return 'warning';
    }

    public function toArray()
    {
        return static::$name;
    }
}