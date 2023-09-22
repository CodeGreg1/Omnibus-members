<?php

namespace Modules\Modules\Support;

use Modules\Base\Support\CrudTypes;
use Modules\Menus\Support\MenuLinkSaver;
use Modules\Menus\Support\MenuLinkDeleter;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Modules\Support\CrudTypeReplacements;

class CrudTypeMenuSaver
{	
	/**
	 * @param array $attributes
	 * @param array $replacements
	 * @param string $menuName
	 * @param string $crudType
	 */
	public function __construct($attributes, $replacements, $menuName, $crudType) 
	{
		$this->attributes = $attributes;
		$this->replacements = $replacements;
		$this->menuName = $menuName;
		$this->crudType = $crudType;
	}

	/**
	 * Handle saving crud type (admin|user) for module
	 * 
	 * @return void
	 */
	public function execute() 
	{
        $menus = (new MenuRepository)->getMenuByName($this->menuName);
        $crud = CrudTypeReplacements::lists($this->crudType);
        $crudRoutePrefix = isset($crud['$CRUD_LOWER_END_DOT$']) && $crud['$CRUD_LOWER_END_DOT$'] != '' ? $crud['$CRUD_LOWER_END_DOT$'] : '';
        $link = $crudRoutePrefix . $this->replacements['$PLURAL_KEBAB_NAME$'] . '.index';
        $parentId = CrudTypes::ADMIN == $this->crudType ? $this->attributes['parent_menu'] : null;

		// check if crud type is included then add menu to specific type menus
        if(isset($this->attributes['included'][$this->crudType]) && $this->attributes['included'][$this->crudType] == 'on') {
            (new MenuLinkSaver([
                'menu_id' => $menus->id,
                'parent_id' => $parentId,
                'icon' => $this->attributes['menu_title']['icon'],
                'label' => $this->attributes['menu_title']['name'],
                'link' => $link,
                'status' => 1
            ]))->execute();
        } else {
        	// delete the menu if route name is exist
        	if($link != '' || !is_null($link)) {
        		(new MenuLinkDeleter(['link' => $link]))->execute();
        	}
        }
	}
}