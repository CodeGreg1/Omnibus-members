<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory, QueryCacheable;

    /**
     * @var string $table
     */
    protected $table = 'settings';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'key',        
        'value',
    ];

    /**
     * @var int $cacheFor
     */
    protected $cacheFor = 3600;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function () {
            static::flushQueryCache();
        });

        static::updated(function () {
            static::flushQueryCache();
        });

        static::deleted(function () {
            static::flushQueryCache();
        });
    }
}
