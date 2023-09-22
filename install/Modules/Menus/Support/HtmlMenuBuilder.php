<?php

namespace Modules\Menus\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Base\Support\CrudTypes;
use Illuminate\Support\Facades\Route;
use Modules\Roles\Support\ExtractModuleName;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Menus\Repositories\MenuLinkRepository;

class HtmlMenuBuilder
{   
	/**
	 * This is useful for checking route with index
	 * 
	 * @param string $endRoute
	 */
	protected static $endRoute = 'index';

	/**
	 * We are using this for a class name active
	 * 
	 * @param string $active
	 */
	protected static $active = ' active';

	/**
	 * Extracting menus to HTML format
	 * 
	 * @param array $items
	 * 
	 * @return string
	 */
	public static function parse($items) 
	{
		$menu = self::get($items['menu']);

		if(!isset($menu['links'])) {
			return null;
		}
		
		if(!auth()->user()->hasAnyPermission(Arr::pluck($menu['links'], 'link'))) {
			return null;
		}

		$links = HtmlMenuLinksBuilder::parse($menu['links']);

		// Don't show if no menu list
		if(count($links) < 1) {
			return '';
		}

		if(isset($items['template']['main_menu_ul'])) {
			$replace = !is_null($menu['class']) ? is_null($menu['class']) : "";

			$html = str_replace(
				'{class}', 
				$replace, 
				$items['template']['main_menu_ul']
			);
		} else {
			$html = '<ul>';
		}

		if(isset($items['header']) && $items['header'] != '') {
			$html .= '<li class="menu-header">' . $items['header'] . '</li>';
		}
		

		foreach($links as $item) {	

			// Check status and permission
			if(Route::has($item['link']) && $item['status'] == 1 && auth()->user()->can($item['link'])) {
				if(isset($items['template']['main_menu_li'])) {

					$mainList = $items['template']['main_menu_li'];

					$mainList = static::menuListHasDropdown($item, $mainList);
					$mainList = str_replace('{label}', utf8_decode($item['label']), $mainList);
					$mainList = str_replace('{class}', is_null($item['class']) ? '' : $item['class'], $mainList);
					$mainList = str_replace('{link}', route($item['link']), $mainList);
					$mainList = str_replace('{icon}', '<i class="'.$item['icon'].'"></i></i>', $mainList);

					$html .= $mainList;
					
				} else {
					$active = self::activeMenu($item);

					$html .= '<li class="' . $item['class'] . '' . $active . '">';
					$html .= '<a href="'.route($item['link']).'">'.utf8_decode($item['label']).'</a>';
				}

				if(isset($item['children'])) {
					$html .= self::children($item['children'], $items['template'] ?? []);
				}

		        $html .='</li>';
			} 
			
		}
		$html .= '</ul>';

		return $html;
		
	}

	/**
	 * Getting the menu by name with language support
	 * - It will display the menus based on the current locale
	 * 
	 * @return array
	 */
	protected static function get($name) 
	{
		$repository = new MenuRepository;

		$menus = $repository->getMenuByName($name);

        return !is_null($menus) ? $menus->toArray() : [];
	}

	/**
	 * Generate sub menu items and generate HTML format
	 * 
	 * @param array $children
	 * @param array $template
	 * 
	 * @return string
	 */
	protected static function children($childrens, $template) 
	{
		$html = isset($template['sub_menu_ul']) 
					? $template['sub_menu_ul'] 
					: '<ul>';

		foreach($childrens as $children) {

			$active = self::activeMenu($children, true);

			// Check status and permission
			if(Route::has($children['link']) && $children['status'] == 1 && auth()->user()->can($children['link'])) {
				if(isset($template['sub_menu_li'])) {
				

					$subList = $template['sub_menu_li'];
					$subList = str_replace('{label}', utf8_decode($children['label']), $subList);
					$subList = str_replace('{class}', $children['class'] . $active, $subList);
					$subList = str_replace('{link}', route($children['link']), $subList);
					$subList = str_replace('{icon}', $children['icon'], $subList);
					$subList = str_replace('{target}', is_null($children['target']) ? '' : $children['target'], $subList);
					$html .= $subList;
				} else {

					$html .= '<li class="'.$active.'"><a class="'.$children['class'].'" href="'.route($children['link']).'">'.utf8_decode($children['label']).'</a></li>';
				}
				
				if ( isset($children['children']) ) {
					$html .= self::children($children['children'], $template);
				}
				$html .= '</li>';
			}
			
		}
		$html .= '</ul>';

		return $html;
	}

	/**
	 * Determine if menu has a dropdown
	 * 
	 * @param array $item
	 * @param string $string
	 * 
	 * @return string
	 */
	protected static function menuListHasDropdown($item, $string) 
	{	
		$active = self::activeMenu($item);

		if (isset($item['children'])) {
			$shortcodes = self::extractShortcode('{has_dropdown:', '}', $string);

			foreach ($shortcodes as $shortcode) {
				$string = str_replace($shortcode, self::getShortcodeValue($shortcode) . $active, $string);
			}
		} else {
			$shortcodes = self::extractShortcode('{has_dropdown:', '}', $string);

			foreach ($shortcodes as $shortcode) {
				$string = str_replace($shortcode, $active, $string);
			}
		}

		return $string;
	}

	/**
	 * Set menu item as active
	 * 
	 * @param array $item
	 * @param bool $isChildren - Check if the menu item is children then we don't check base on route name by crud type
	 * 
	 * @return string
	 */
	protected static function activeMenu($item, $isChildren = false) 
	{
		if(in_array(Route::currentRouteName(), self::extractMenuRoutes($item))) {
			return static::$active;
		}

		if($isChildren) {
			$crudLink = self::extractRouteNameModuleWithCrudType($item['link']);
			
			//check if current route and current menu item is related crud module
			if(Str::contains(Route::currentRouteName(), $crudLink)) {
				return static::$active;
			}

			return '';
		}

		foreach(self::extractMenuRoutes($item) as $link) {
			// check if route has crud type (admin|user)
			if(self::checkIfRouteHasCrudType($link)) {
				if(self::extractRouteNameModuleWithCrudType(Route::currentRouteName()) == self::extractRouteNameModuleWithCrudType($link)) {
					return static::$active;
				}
			} else {
				if(self::extractRouteNameModule(Route::currentRouteName()) == self::extractRouteNameModule($link)) {
					return static::$active;
				}
			}
		}
	}

	/**
	 * Extract route name module with CRUD type
	 * 
	 * @param string $route
	 * 
	 * @return string
	 */
	protected static function extractRouteNameModuleWithCrudType($route) 
	{
		$currentRouteName = Route::currentRouteName();

		$array = explode('.', $route);
		$currentRouteNameArray = explode('.', $currentRouteName);

		if(static::$endRoute == Arr::last($currentRouteNameArray)) {
			return $route;
		}

		return implode('.', array_slice($array, 0, (count($array) - 1)));
	}

	/**
	 * Check if route has a CRUD type
	 * 
	 * @param string $route
	 * 
	 * @return boolean
	 */
	protected static function checkIfRouteHasCrudType($route) 
	{
		$array = explode('.', $route);

		return count($array) >= 3 && in_array(reset($array), CrudTypes::lists());
	}

	/**
	 * Extract route name module
	 * 
	 * @param string
	 * 
	 * @return string
	 */
	protected static function extractRouteNameModule($route) 
	{
		$array = explode('.', $route);
		return (new ExtractModuleName($array))->get();
	}

	/**
	 * Extract menu routes
	 * 
	 * @param array $item
	 * @param string $child
	 * 
	 * @return array
	 */
	protected static function extractMenuRoutes($item, $child = null, $links = []) 
	{	
		if(!is_null($child)) {
			foreach($item as $itm) {
				$links[] = $itm['link'];
			}
		}

		if(is_null($child) && isset($item['link'])) {
			$links[] = $item['link'];
		}

		if(isset($item['children'])) {
			return self::extractMenuRoutes($item['children'], 1, $links);
		}

		return $links;
	}

	/**
	 * Get shortcode value
	 * 
	 * @param string $shortcode
	 * 
	 * @return string
	 */
	protected static function getShortcodeValue($shortcode) 
	{
		$shortcode = explode(':', $shortcode);
		return str_replace('}', '', end($shortcode));
	}

	/**
	 * Extract shortcodes
	 * 
	 * @param string $start
	 * @param string $end
	 * @param string $content
	 * @param array $result
	 * 
	 * @return array
	 */
	protected static function extractShortcode($start, $end, $content, $result=[]) 
    {   
        preg_match_all('/'.$start.'(.*?)'.$end.'/s', $content, $match);

        return $match[0] ?? [];
    }
}