<?php

namespace Modules\Affiliates\States\CommissionStatus;

class Rejected extends CommissionStatusState
{
    public static $name = 'rejected';

    public function color(): string
    {
        return 'danger';
    }

    public function toArray()
    {
        return static::$name;
    }
}