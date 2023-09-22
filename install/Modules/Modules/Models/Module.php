<?php

namespace Modules\Modules\Models;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory, QueryCacheable;

    /**
     * @var string $table
     */
    protected $table = 'modules';

    /**
     * @var array $fillable
     */
    protected $fillable = [
    	'name',
        'handle',
        'table_name',
    	'attributes',
        'is_core',
        'is_ran_npm',
        'is_ran_migration',
        'pro_access'
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
     * Handle decoding json module attributes
     */
    public function getAttributesAttribute($value) 
    {
        return json_decode($value, true);
    }

    /**
     * Get module relation records
     */
    public function relations()
    {
        return $this->hasMany(ModuleRelation::class, 'module_related_id', 'id');
    }
}
