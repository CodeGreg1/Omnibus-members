<?php

namespace Modules\Pages\States;

use Illuminate\Support\Str;
use Spatie\ModelStates\State;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\ModelStates\Attributes\DefaultState;
use Spatie\ModelStates\Attributes\AllowTransition;

#[
    AllowTransition(Draft::class, Published::class),
    AllowTransition(Published::class, Draft::class),
    DefaultState(Draft::class),
]
abstract class StatusState extends State implements Arrayable
{
    abstract public function color(): string;

    abstract public function toArray();

    public function getLabel()
    {
        return Str::title(str_replace('_', ' ', static::$name));
    }
}
