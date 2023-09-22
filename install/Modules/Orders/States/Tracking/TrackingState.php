<?php

namespace Modules\Orders\States\Tracking;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Spatie\ModelStates\Attributes\AllowTransition;
use Spatie\ModelStates\Attributes\DefaultState;
use Spatie\ModelStates\State;

#[
    AllowTransition(Pending::class, InTransit::class),
    AllowTransition(Pending::class, Cancelled::class),
    AllowTransition(Cancelled::class, Returned::class),
    AllowTransition(InTransit::class, Cancelled::class),
    AllowTransition(InTransit::class, Delivered::class),
    AllowTransition(Cancelled::class, Pending::class),
    DefaultState(Pending::class),
]
abstract class TrackingState extends State implements Arrayable
{
    abstract public function color(): string;

    abstract public function toArray();

    public function getLabel()
    {
        return Str::title(str_replace('_', ' ', static::$name));
    }
}