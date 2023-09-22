<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Modules\Roles\Repositories\RoleRepository;
use Modules\Modules\Support\GetCreatingModuleRoutes;

class ModuleRemovePermissions {

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
     * Handle removing module permission when deleting the module
     * 
     * @return void
     */
    public function execute() 
    {
    	$roles = new RoleRepository;
        $modulePermissions = (new GetCreatingModuleRoutes($this->attributes))->lists();

    	foreach($this->attributes['roles'] as $role) {
    		$role = $roles->find($role);

    		$role->syncPermissions(array_diff(
                $role->permissions->pluck('name')->toArray(), 
                $modulePermissions
            ));
    	}

        // remove module permissions
        $this->removePermission($modulePermissions);
    }

    /**
     * Haandle to remove the permission from the permissions table
     * 
     * @param array $permissions
     * 
     * @return void
     */
    protected function removePermission($permissions) 
    {
        Permission::whereIn('name', $permissions)->delete();
    }
}