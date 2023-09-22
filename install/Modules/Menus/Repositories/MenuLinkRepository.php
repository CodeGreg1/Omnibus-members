<?php

namespace Modules\Menus\Repositories;

use Modules\Menus\Models\MenuLink;
use Modules\Base\Repositories\BaseRepository;

class MenuLinkRepository extends BaseRepository
{
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = MenuLink::class;

    /**
     * Get the related menu link by link
     * 
     * @param string $link
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getRelatedLinksByLink($link) 
    {
        $menuLink = MenuLink::with('relatedLinks')->where('menu_links.link', $link)->first();

        return $menuLink->relatedLinks;
    }
}