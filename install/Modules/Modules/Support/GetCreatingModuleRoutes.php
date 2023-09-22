<?php

namespace Modules\Modules\Support;

use Modules\Base\Support\CrudTypes;
use Modules\Modules\Support\CrudTypeReplacements;

class GetCreatingModuleRoutes {

	/**
	 * @var array $attributes
	 */
	protected $attributes;

	/**
	 * @param array $attributes
	 */
	public function __construct(array $attributes) 
	{
		$this->attributes = $attributes;
	}

	/**
	 * Get the default list of module routes
	 * 
	 * @return array
	 */
	public function lists() 
	{
		$result = [];

		$crudTypes = CrudTypes::lists();

		// remove admin
		if(!isset($this->attributes['included']['admin'])) {
			if (($key = array_search('admin', $crudTypes)) !== false) {
			    unset($crudTypes[$key]);
			}
		}

		// remove user
		if(!isset($this->attributes['included']['user'])) {
			if (($key = array_search('user', $crudTypes)) !== false) {
			    unset($crudTypes[$key]);
			}
		}

    	foreach($crudTypes as $crudType) {
	    	foreach($this->attributes['routes'] as $defaultRoute) {
	    		$result[] =  CrudTypeReplacements::lists($crudType)['$CRUD_LOWER_END_DOT$'] . $this->attributes['module'] . $defaultRoute;
	    	}
    	}

    	return $result;
	}

}