<?php

namespace Modules\Menus\Support;

use Modules\Menus\Models\MenuLink;
use Modules\Menus\Repositories\MenuLinkRepository;

class MenuLinkDeleter
{   
	/**
	 * @var array protected
	 */
	protected $attributes = [];

	/**
	 * @param array $attributes
	 */
	public function __construct($attributes) 
	{
		$this->attributes = $attributes;
	}    

	/**
	 * Delete menu link
	 * 
	 * @return MenuLink
	 */
	public function execute() 
	{
		$menuLink = new MenuLinkRepository;

		$menuLink = $menuLink->findBy('link', $this->attributes['link']);

		if(!is_null($menuLink)) {
			return $menuLink->delete();
		}
	}
}