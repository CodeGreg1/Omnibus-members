<?php

namespace Modules\Roles\Support;

use Modules\Base\Support\CrudTypes;

class ExtractModuleName
{ 	
	/**
	 * @var array $permission
	 */
	protected $permission;

	/**
	 * @param array $permission
	 */
	public function __construct(array $permission) 
	{
		$this->permission = $permission;
	}

	/**
	 * Handle on extracting module name
	 * 
	 * @return string
	 */
	public function get() 
	{
        // check if first value is admin of array
        if(in_array(reset($this->permission), CrudTypes::lists(['api', 'site']))) {
            array_shift($this->permission);

            return reset($this->permission);
        }
        
        return reset($this->permission);
	}
}