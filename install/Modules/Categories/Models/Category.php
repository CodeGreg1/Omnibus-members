<?php

namespace Modules\Categories\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Categories\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CategoryTypes\Models\CategoryType;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use SoftDeletes, HasFactory, QueryCacheable;

    /**
     * @var string $table
     */
    protected $table = 'categories';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'category_type_id',        
        'parent_id',        
        'name',        
        'description',        
        'color',
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

    public function category_type()
    {
        return $this->belongsTo(CategoryType::class, 'category_type_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function getByType($type) 
    {
        return $this->whereHas('category_type', function($query) use ($type)  {
            $query->where('type', $type);
        })->get();
    }
}
