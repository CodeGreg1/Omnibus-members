<?php

namespace Modules\Carts\Services;

use Illuminate\Support\Collection;

class ConditionCollection extends Collection
{
    /**
     * check if condition exist from cart
     *
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return !!$this->firstWhere(function ($condition) use ($name) {
            return $condition->getName() === $name;
        });
    }

    /**
     * get cart condition by name
     *
     * @param $name
     * @param $default
     * @return ItemConditionCollection
     */
    public function get($name, $default = null)
    {
        return $this->firstWhere(function ($condition) use ($name) {
            return $condition->getName() === $name;
        }) ?? value($default);
    }
}