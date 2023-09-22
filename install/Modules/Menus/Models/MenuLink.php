<?php

namespace Modules\Menus\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Menus\Support\MenuLinkType;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuLink extends Model
{
    use HasFactory, QueryCacheable;

    /**
     * @var string $table
     */
    protected $table = 'menu_links';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'menu_id',
        'parent_id',
        'ordering',
        'icon',
        'label',
        'type',
        'link',
        'class',
        'last_ordering',
        'target',
        'status'
    ];

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
     * Get related links
     */
    public function relatedLinks()
    {
        return $this->hasMany(MenuLink::class, 'menu_id', 'menu_id');
    }

    public function getRoute()
    {
        try {
            switch ($this->type) {
                case MenuLinkType::BLOG:
                    $route = route('blogs.show', $this->link);
                    break;
                case MenuLinkType::PAGE:
                    $route = route('pages.show', $this->link);
                    break;
                case MenuLinkType::CUSTOM:
                    $route = $this->link;
                    break;
                default:
                    $route = route($this->link);
            }

            return $route;
        } catch (\Exception $e) {
            report($e);

            return null;
        }
    }
}
