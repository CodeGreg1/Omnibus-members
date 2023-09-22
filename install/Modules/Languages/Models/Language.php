<?php

namespace Modules\Languages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Models\Country;

class Language extends Model
{
    use SoftDeletes, HasFactory, QueryCacheable;

    /**
     * @var string $table
     */
    protected $table = 'languages';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'title',        
        'code',        
        'direction',        
        'flag_id',        
        'active',
    ];

    /**
     * @var array DIRECTION_SELECT
     */
    public const DIRECTION_SELECT = [
    	'ltr' => 'Left To Right',	
        // 'rtl' => 'Right To Left'
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
     * Get direction display - this is useful for displaying in the datatable row
     * 
     * @return string
     */
    public function getDirectionAttribute($value)
    {
        return self::DIRECTION_SELECT[$value] ?? '';
    }

    /**
     * Get the language country as flag
     */
    public function flag()
    {
        return $this->belongsTo(Country::class, 'flag_id');
    }
}
