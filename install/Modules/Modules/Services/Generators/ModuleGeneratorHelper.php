<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;

class ModuleGeneratorHelper
{
	/**
	 * Handle getting value by key from the attributes provided
	 * 
	 * @param array $attributes
	 * @param string $key
	 * 
	 * @return string|null
	 */
	public function getValueByKey($attributes, $key) 
	{
		if(is_array($attributes)) {
			foreach($attributes as $keyField => $field) {
				if(isset($field['name']) && $field['name'] == $key) {
					return $field['value'];
				}
	        }
		}
	}

	/**
	 * Handle getting multiple value by key from the attributes provided
	 * 
	 * @param array $attributes
	 * @param string $key
	 * 
	 * @return array
	 */
	public function getMultipleValueByKey($attributes, $key) 
	{
		$values = [];

		if(is_array($attributes)) {
			foreach($attributes as $keyField => $field) {
				if(isset($field['name']) && $field['name'] == $key) {
					$values[] = $field['value'];
				}
	        }
		}

		return $values;
	}

	/**
	 * Handle replacing shortcode with the corresponding value
	 * 
	 * @param array $replacements
	 * @param string $content
	 * 
	 * @return string
	 */
	public function replace($replacements, $content) 
    {   
        foreach($replacements as $shortcode=>$value) {
            $content = str_replace($shortcode, $value, $content);
        }
        return $content;
    }
}