<?php

namespace Modules\Base\Support;

use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Modules\Roles\Support\ExtractModuleName;

class CurrentModule {

	/**
	 * Get the module name by route
	 * 
	 * @return string|null
	 */
	public function get() 
	{
		$route = Request::route();

		if(isset($route->action['namespace'])) {
			$namespace = $route->action['namespace'];
			$arrayNamespace = explode('\\', $namespace);

			if(reset($arrayNamespace) == $this->getModuleFolder()) {
				return strtolower($arrayNamespace[1]);
			}
		}
	}

	/**
	 * Get the modules folder
	 * 
	 * @return string
	 */
	private function getModuleFolder() 
	{
		$path = Module::getPath();
		$arrayPath = explode('\\', $path);

		return end($arrayPath);
	}

}