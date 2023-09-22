<?php

namespace Modules\Menus\Repositories;

use Modules\Menus\Models\Menu;
use Modules\Menus\Models\MenuLink;
use Modules\Menus\Support\MenuType;
use Modules\Base\Repositories\BaseRepository;
use Modules\Base\Support\Route\RouteNameParser;

class MenuRepository extends BaseRepository
{
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Menu::class;

    /**
     * Get menu with related language menus
     * 
     * @param int $id
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getMenuWithRelatedById($id) 
    {
        return Menu::where('id', $id)
            ->orWhere('parent_id', $id)
            ->get()
            ->map(function ($menu) {
                $typeLink = $menu->type == MenuType::FRONTEND ? 'frontend_links' : 'links';
                $menu->{$typeLink}->map(function ($link) {
                    $text = '';

                    if ($link->type === 'Default') {
                        $text = (new RouteNameParser)->parse($link->link) . ' (' . $link->link . ')';
                    }

                    if ($link->type === 'Page' || $link->type === 'Blog') {
                        $text = ucwords(str_replace('-', ' ', $link->link));
                    }

                    $link->link_data = [
                        'id' => $link->link,
                        'text' => $text
                    ];

                    return $link;
                });

                return $menu;
            });
    }

    /**
     * Get menu by name with locale support
     * 
     * @param string $name
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getMenuByName($name) 
    {
        $locale = app()->getLocale();

        if($locale != 'en') {
            return  Menu::leftJoin('menus as m2', 'menus.id', '=', 'm2.parent_id')
                ->leftJoin('languages', 'm2.language_id', '=', 'languages.id')
                ->where('languages.code', $locale)
                ->where('menus.name', $name)
                ->with(['links' => function ($query) {
                    $query->where('status', 1);
                }])
                ->select([
                    'm2.id as id', 
                    'm2.parent_id as parent_id',
                    'm2.language_id as language_id',
                    'm2.type as type',
                    'm2.name as name',
                    'm2.class as description',
                    'm2.class as class'
                ])
                ->first();
        }

        return Menu::where('name', $name)
            ->with(['links' => function ($query) {
                $query->where('status', 1);
            }])
            ->first();
    }

    /**
     * Pluck menu link id's
     * 
     * @param int $id
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function pluckMenuLinksIds($id) 
    {
        return MenuLink::whereMenuId($id)->pluck('id');
    }

    /**
     * Get the english menus
     * 
     * @return \Illuminate\Database\Eloquent\Model 
     */
    public function getEnglishMenus() 
    {
        return Menu::whereNull('parent_id')->get();
    }

}