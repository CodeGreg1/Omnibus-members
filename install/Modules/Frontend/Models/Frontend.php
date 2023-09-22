<?php

namespace Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Frontend extends Model
{
    use SoftDeletes, HasFactory, QueryCacheable;

    /**
     * @var string $table
     */
    protected $table = 'frontends';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',        
        'description',        
        'type',        
        'data',        
        'section_heading',        
        'section_sub_heading',        
        'section_description',        
        'section_background_color',        
        'section_background_image',        
        'template',        
        'dynamic',
    ];

	/**
     * @var array TYPE_SELECT
     */
    public const TYPE_SELECT = [
    	'section' => 'Section',	
        'element' => 'Element'
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
     * Get the frontend's type
     *
     * @param $value
     * 
     * @return string
     */
    public function getTypeAttribute($value)
    {
        return self::TYPE_SELECT[$value] ?? '';
    }
}
