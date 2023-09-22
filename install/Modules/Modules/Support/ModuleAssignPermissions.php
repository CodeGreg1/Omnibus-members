<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Modules\Roles\Repositories\RoleRepository;

class ModuleAssignPermissions {

    /**
     * @var array $attributes
     */
	protected $attributes = [];

    /**
     * @param array $attributes
     */
    public function __construct($attributes) 
    {
    	$this->attributes = $attributes;
    }

    /**
     * Handle assigning module permissions
     * 
     * @return void
     */
    public function execute() 
    {
    	$roles = new RoleRepository;
        $moduleRoutes = (new GetCreatingModuleRoutes($this->attributes))->lists();

    	foreach($this->attributes['roles'] as $role) {
    		$role = $roles->find($role);

    		$role->syncPermissions(array_merge(
    			$role->permissions->pluck('name')->toArray(),
    			$moduleRoutes
    		));
    	}
    }
}