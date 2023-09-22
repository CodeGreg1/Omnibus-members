<?php

namespace Modules\Base\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait Multitenantable
{
    public static function bootMultitenantable()
    {
        if (!app()->runningInConsole()) {
            static::creating(function ($model) {
                // if (!auth()->user()->isAdmin()) {
                    $model->user_id = auth()->id();
                // }
            });

            static::addGlobalScope('user_id', function (Builder $builder) {
                if (!auth()->user()->isAdmin()) {
                    $field = sprintf('%s.%s', $builder->getQuery()->from, 'user_id');

                    $builder->where($field, auth()->id())->orWhereNull($field);
                }
            });
        }
    }
}