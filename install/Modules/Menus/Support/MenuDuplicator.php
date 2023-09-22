<?php

namespace Modules\Menus\Support;

use Modules\Menus\Support\MenuType;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Menus\Support\HtmlMenuLinksBuilder;
use Modules\Menus\Repositories\MenuLinkRepository;
use Modules\Languages\Repositories\LanguagesRepository;

class MenuDuplicator
{  
	/**
	 * Language code more details here: https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
	 * 
	 * @var string protected
	 */
	protected $lang;

	/**
	 * @var string $lang
	 */
	public function __construct($lang) 
	{
		$this->lang = $lang;
	}

	/**
	 * Execute deplicating menu to another language
	 * 
	 * @return void
	 */
	public function execute() 
	{
		$menus = new MenuRepository;

		$menus = $menus->getEnglishMenus();

		foreach($menus as $menu) {
			$links = $menu->type != MenuType::FRONTEND ? 'links' : 'frontend_links';

			$createdMenu = $this->createMenu($menu);

			$this->createMenuLinks($createdMenu, $menu->{$links});
		}
	}

	/**
	 * Create the duplicated menu for the selected language
	 * 
	 * @var Model $menu
	 * 
	 * @return Model
	 */
	protected function createMenu($menu) 
	{
		$menus = new MenuRepository;
		$language = $this->getLanguage();

		return $menus->create([
			'parent_id' => $menu->id,
            'language_id' => $language->id,
            'name' => $this->setName($menu),
            'description' => $this->setDescription($menu, $language),
            'type' => $menu->type,
            'class' => $menu->class
        ]);
	}

	/**
	 * Create menu links for the main menu of the selected language
	 * 
	 * @var Model $menu
	 * @var Model $menuAvailableLinks
	 * 
	 * @return void
	 */
	protected function createMenuLinks($menu, $menuAvailableLinks) 
	{
		$menuLinks = HtmlMenuLinksBuilder::parse($menuAvailableLinks->toArray());

		$this->saveSubMenuItems($menu, $menuLinks);
	}

	/**
	 * Get the language details
	 * 
	 * @return Model
	 */
	protected function getLanguage() 
	{
		$languages = new LanguagesRepository;

		return $languages->getByCode($this->lang);
	}

	/**
	 * Set the name of menu with specific language code
	 * 
	 * @return string
	 */
	protected function setName($menu) 
	{
		return $menu->name . '_' . $this->lang;
	}

	/**
	 * Set the descripton of menu
	 * 
	 * @var Model $menu
	 * @var Model $language
	 * 
	 * @return string
	 */
	protected function setDescription($menu, $language) 
	{
		return ucfirst($language->title) . ' ' . strtolower($menu->description);
	}

	/**
	 * Save the menu sub items
	 * 
	 * @var Model $menu
	 * @var array $menuLinks
	 * @var int|null $parentId
	 * 
	 * @return void
	 */
	protected function saveSubMenuItems($menu, $menuLinks, $parentId = null) 
	{
		$menuLinksModel = new MenuLinkRepository;

        foreach ($menuLinks as $menuLink) {
            $menuLinkData = [
                'menu_id' => $menu->id,
                'ordering' => $menuLink['ordering'],
                'label' => $menuLink['label'],
                'icon' => $menuLink['icon'],
                'type' => $menuLink['type'],
                'link' => $menuLink['link'],
                'class' => $menuLink['class'],
                'target' => $menuLink['target'],
                'status' => $menuLink['status'],
                'parent_id' => $parentId ?? null
            ];

            $createdMenuLink = $menuLinksModel->create($menuLinkData);

            if ( isset($menuLink['children']) ) {
                $this->saveSubMenuItems($menu, $menuLink['children'], $createdMenuLink->id);
            }
        }
	}
}