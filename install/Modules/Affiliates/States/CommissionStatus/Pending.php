<?php

namespace Modules\Affiliates\States\CommissionStatus;

class Pending extends CommissionStatusState
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