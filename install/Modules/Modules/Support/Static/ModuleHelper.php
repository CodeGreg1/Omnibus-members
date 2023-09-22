<?php

namespace Modules\Modules\Support\Static;

use Illuminate\Support\Str;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class ModuleHelper 
{
	/**
	 * Module helper that can get value by key from the module attributes
	 * 
	 * @param array $attributes
	 * @param string $key
	 * 
	 * @return string
	 */
	public static function getValueByKey($attributes, $key) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, $key);
	}

}