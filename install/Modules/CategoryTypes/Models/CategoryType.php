<?php

namespace Modules\CategoryTypes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryType extends Model
{
    use SoftDeletes, HasFactory, QueryCacheable;

    /**
     * @var string $table
     */
    protected $table = 'category_types';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'type',        
        'name',        
        'description',
    ];

    /**
     * Append modified field values
     */
    protected $appends = [
        'type_name'
    ];

	/**
     * @var array TYPE_SELECT
     */
    public const TYPE_SELECT = [
    	'Blog' => 'Blog',	
        'Photo' => 'Photo',
        'Support' => 'Support'
    ];

    /**
     * @var string TYPE_BLOG
     */
    public const TYPE_BLOG = 'Blog';

    /**
     * @var string TYPE_PHOTO
     */
    public const TYPE_PHOTO = 'Photo';

    /**
     * @var string TYPE_SUPPORT
     */
    public const TYPE_SUPPORT = 'Support';

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
     * Get type name
     *
     * @param $value
     * 
     * @return string
     */
    public function getTypeNameAttribute($value)
    {
        return ucwords($this->type . ' -> ' . $this->name);
    }

    /**
     * Get the category type's type
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
