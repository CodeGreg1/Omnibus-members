<?php

namespace Modules\Menus\Support;

class HtmlMenuLinksBuilder 
{
    /**
     * @var array $options
     */
	protected static $options = [
        'parent_id_column_name' => 'parent_id',
        'children_key_name' => 'children',
        'id_column_name' => 'id'
    ];

    /**
     * Parsing HTML menu links
     * 
     * @param array $elements
     * @param int $parentId
     * 
     * @return array
     */
	public static function parse(array $elements, $parentId = 0) 
	{
		$branch = array();
        foreach ($elements as $element) {
            if ($element[self::$options['parent_id_column_name']] == $parentId) {
                $children = self::parse($elements, $element[self::$options['id_column_name']]);
                if ($children) {
                    $element[self::$options['children_key_name']] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
	}
}