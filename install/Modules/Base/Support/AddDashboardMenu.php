<?php

namespace Modules\Base\Support;

use \Exception;
use Modules\Menus\Models\Menu;
use Illuminate\Support\Facades\DB;
use Modules\Menus\Models\MenuLink;
use Spatie\Permission\Models\Role;
use Modules\Menus\Support\MenuType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Permission;

class AddDashboardMenu {
	/**
	 * **************************************************************
	 * NOTE: This function will not run in the installation process *
	 * **************************************************************
	 * 
	 * This function will add dashboard menu link (this is for menu link only not for main menu)
	 * 
	 * @var array $menu - Menu in array with label and link
	 *  Example: [
	 *   'lang' => 'en' - for the value of lang you can add another langauge supported in the system like de for German
	 *	 'label' => 'Database Migration',
	 *	 'link' => 'admin.migration.index',
	 *   'icon' => 'fas fa-cog'
	 *	]
	 * @var string $type = Admin|User
	 * @var string $beforeMenuLabel - This is the position where the ordering value before of the menu
	 * @var string|null $parentLabel - Then parent label of the menu; leave null if no parent
	 * 
	 * Example: If menu 1 is in order as #3 then the menu 2 will be in order #4
	 * 
	 * @return void
	 */
	public function execute(array $menu, $type, $beforeMenuLabel, $parentLabel = null) 
	{
		// Skip this for installation
		if(!Request::routeIs('install.install-now')) {
			// Get the parent ID
			$parent = MenuLink::where('label', $parentLabel)->first();
			$parent = !is_null($parent) ? $parent->id : null;

			// Get the menu ordering
			$ordering = $this->getOrdering($beforeMenuLabel);

			// Save the menu
			MenuLink::updateOrCreate([
				'label' => $menu['label']
			],
			[
				'menu_id' => $this->getMenuId($type, $menu),
				'parent_id' => $parent,
				'ordering' => $ordering,
				'label' => $menu['label'],
				'link' => $menu['link'],
				'icon' => $menu['icon']
			]);

			// Update the next menus ordering
			$this->updateNextMenuOrdering($menu['label'], $ordering, $parent);
		}
	}

	/**
	 * Update the next of the menu ordering
	 * 
	 * @var string $label - This is the menu label which we will exclude with the update in this function
	 * @var int $ordering
	 * @var int parent
	 * 
	 * @return void
	 */
	private function updateNextMenuOrdering($label, $ordering, $parent) 
	{
		if(!is_null($parent)) {
			$menuLinks = MenuLink::where('parent_id', $parent)
				->where('ordering', '>=', $ordering)
				->whereNot('label', $label)
				->get();

			foreach($menuLinks as $menuLink) {
				$menuLink->update([
					'ordering' => ($menuLink->ordering + 1)
				]);
			}
		}
	}

	/**
	 * Get the menu ordering
	 * 
	 * @var string $beforeMenuLabel
	 * 
	 * @return int
	 */
	private function getOrdering($beforeMenuLabel) 
	{
		$beforeMenu = MenuLink::where('label', $beforeMenuLabel)->first();

		if(!is_null($beforeMenu)) {
			return ($beforeMenu->ordering + 1);
		}
		
		return 1;
	}

	/**
	 * Get menu id
	 * 
	 * @var string $type
	 * @var array $menu
	 * 
	 * @return int|Exception
	 */
	private function getMenuId($type, $menu) 
	{
		$model = Menu::where('type', $type);

		if($menu['lang'] != 'en') {
			$model = $model->where('name', $type . '_' . $menu['lang']);
		}
		
		$model = $model->first();

		if(is_null($model)) {
			throw new Exception("Menu type: '$type' provided is not existing.", 1);
		}

		return $model->id;
	}

}