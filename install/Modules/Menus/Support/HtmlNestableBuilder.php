<?php

namespace Modules\Menus\Support;

class HtmlNestableBuilder
{   
	/**
	 * Building Nestable HTML
	 * 
	 * @param array $items
	 * 
	 * @return string
	 */
	public static function parse($items) 
	{
		$html = '';
		foreach ($items as $item) {
			$html .= '<li class="dd-item dd3-item" data-id="' . $item['id'] . '" id="' . $item['id'] . '" data-icon="' . $item['icon'] . '" data-type="' . $item['type'] . '" data-name="' . $item['label'] . '" data-link-title="' . $item['link_data']['text'] . '" data-link="' . $item['link'] . '" data-className="' . $item['class'] . '" data-target="' . $item['target'] . '" data-status="' . $item['status'] . '">';
			$html .= '<div class="dd-handle dd3-handle"></div>';
			$html .= '<div class="dd3-content">';
			$html .= '<span class="item-content"><span class="'.$item['icon'].'"></span> '.$item['label'].'</span>';
			$html .= '<span class="float-right menu-item-actions"><i class="fas fa-edit edit-menu-item-data" title="Edit"></i><i class="fas fa-times remove-menu-item" title="Remove"></i></span>';
			$html .= '</div>';
			if(isset($item['children'])) {
				$html .= self::children($item['children']);
			}
	        $html .='</li>';
		}

		return $html;
		
	}

	/**
	 * Generate Nestable HTML Sub Items
	 * 
	 * @param array $childrens
	 * 
	 * @return string
	 */
	protected static function children($childrens) 
	{
		$html = '<ol class="dd-list">';
		foreach ($childrens as $children) {
			$html .= '<li class="dd-item dd3-item" data-id="' . $children['id'] . '" id="' . $children['id'] . '" data-icon="' . $children['icon'] . '" data-name="' . $children['label'] . '" data-type="' . $children['type'] . '" data-link-title="' . $children['link_data']['text'] . '" data-link="' . $children['link'] . '" data-className="' . $children['class'] . '" data-target="' . $children['target'] . '" data-status="' . $children['status'] . '">';
			$html .= '<div class="dd-handle dd3-handle"></div>';
			$html .= '<div class="dd3-content">';
			$html .= '<span class="item-content"><span class="'.$children['icon'].'"></span> '.$children['label'].'</span>';
			$html .= '<span class="float-right menu-item-actions"><i class="fas fa-edit edit-menu-item-data" title="Edit"></i><i class="fas fa-times remove-menu-item" title="Remove"></i></span>';
			$html .= '</div>';
			if ( isset($children['children']) ) {
				$html .= self::children($children['children']);
			}
			$html .= '</li>';
		}
		$html .= '</ol>';

		return $html;
	}
}