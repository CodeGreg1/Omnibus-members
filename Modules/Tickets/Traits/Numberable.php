<?php

namespace Modules\Tickets\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait Numberable
{
    public static function bootNumberable()
    {
        static::creating(function ($model) {
            $model->number = ticket_number();
        });
    }
}