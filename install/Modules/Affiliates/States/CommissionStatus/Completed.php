<?php

namespace Modules\Affiliates\States\CommissionStatus;

class Completed extends CommissionStatusState
{
    public static $name = 'completed';

    public function color(): string
    {
        return 'primary';
    }

    public function toArray()
    {
        return static::$name;
    }
}