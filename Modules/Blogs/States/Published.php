<?php

namespace Modules\Blogs\States;

class Published extends StatusState
{
    public static $name = 'published';

    public function color(): string
    {
        return 'primary';
    }

    public function toArray()
    {
        return static::$name;
    }
}