<?php

namespace Modules\Base\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait Userable
{
    public static function bootUserable()
    {
        if (!app()->runningInConsole()) {
            static::creating(function ($model) {
                $model->user_id = auth()->id();
            });
        }
    }
}