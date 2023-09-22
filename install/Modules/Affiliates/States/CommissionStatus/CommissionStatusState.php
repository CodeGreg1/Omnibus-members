<?php

namespace Modules\Affiliates\States\CommissionStatus;

use Illuminate\Support\Str;
use Spatie\ModelStates\State;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\ModelStates\Attributes\DefaultState;
use Spatie\ModelStates\Attributes\AllowTransition;

#[
    AllowTransition(Pending::class, Completed::class),
    AllowTransition(Pending::class, Rejected::class),
    DefaultState(Pending::class),
]
abstract class CommissionStatusState extends State implements Arrayable
{
    abstract public function color(): string;

    abstract public function toArray();

    public function getLabel()
    {
        return Str::title(str_replace('_', ' ', static::$name));
    }
}
