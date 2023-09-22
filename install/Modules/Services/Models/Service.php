<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Services\Support\ServiceStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use SoftDeletes, HasFactory, QueryCacheable;

    /**
     * @var string $table
     */
    protected $table = 'services';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'icon',        
        'title',        
        'content',        
        'visibility',
    ];

	/**
     * @var array $appends
     */
    protected $appends = [        
        'visibility_details'
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

    /**
     * Get the visibility's details
     * 
     * @return array
     */
    public function getVisibilityDetailsAttribute()
    {
        return ServiceStatus::get($this->visibility) ?? [];
    }
}
