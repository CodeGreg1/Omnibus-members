<?php

namespace Modules\Pages\States;

class Draft extends StatusState
{
    public static $name = 'draft';

    public function color(): string
    {
        return 'danger';
    }

    public function toArray()
    {
        return static::$name;
    }
}