<?php

namespace Modules\Menus\Models;

use Modules\Menus\Models\MenuLink;
use Modules\Languages\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory, QueryCacheable;

    /**
     * @var string $table
     */
    protected $table = 'menus';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'parent_id',
        'language_id',
        'type',
        'name',
        'description',
        'class'
    ];

    /**
     * @var array $with
     */
    protected $with = ['links', 'frontend_links'];

    /**
     * @var int $cacheFor
     */
    protected $cacheFor = 86400;

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
     * Get menu links
     */
    public function links() 
    {
        return $this->hasMany(MenuLink::class)
            ->orderBy('last_ordering', 'asc')
            ->orderBy('ordering', 'asc');  
    }

    /**
     * Get menu frontend links
     */
    public function frontend_links() 
    {
        return $this->hasMany(MenuLink::class)
            ->orderBy('ordering', 'asc');  
    }

    /**
     * Get menu language
     */
    public function language() 
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Get related menu
     */
    public function related() 
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }
}
