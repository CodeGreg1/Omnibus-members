<?php

namespace Modules\Menus\Support;

use Modules\Menus\Models\MenuLink;
use Modules\Menus\Repositories\MenuLinkRepository;

class MenuLinkSaver
{   
	/**
	 * @var array protected
	 */
	protected $attributes = [];

	/**
	 * @var array $attributes
	 */
	public function __construct($attributes) 
	{
		$this->attributes = $attributes;
	}    

	/**
	 * Save menu link
	 * 
	 * @return MenuLink
	 */
	public function execute() 
	{
		$menuLink = new MenuLinkRepository;

		return $menuLink->updateOrCreate(['link' => $this->attributes['link']], array_merge($this->attributes, [
			'ordering' => $this->ordering()
		]))->flushQueryCache();
		
	}

	/**
	 * Handle ordering menu link
	 * 
	 * @return int
	 */
	protected function ordering() 
	{
		$menuLink = new MenuLinkRepository;

		if($this->attributes['parent_id'] == '') {
			$ordering = $menuLink->whereNull('parent_id')->count();
		} else {
			$ordering = $menuLink->whereNotNull('parent_id')->count();
		}

		return $ordering + 1;
	}
}