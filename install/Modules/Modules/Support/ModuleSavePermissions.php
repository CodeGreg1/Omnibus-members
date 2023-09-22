<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Roles\Support\RoleType;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Modules\Roles\Repositories\RoleRepository;
use Modules\Base\Support\Route\RouteNameParser;
use Modules\Modules\Support\GetCreatingModuleRoutes;

class ModuleSavePermissions {

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
     * Handle on creating/updating module permissions
     * 
     * @return void
     */
    public function execute() 
    {	
    	$roles = new RoleRepository;
    	$permissions = (new GetCreatingModuleRoutes($this->attributes))->lists();

    	foreach($permissions as $permission) {

            // get the permission prefix to determine if admin/user
            $permissionPrefix = Arr::first(explode('.', $permission));

    		$entry = Permission::updateOrCreate(['name' => $permission], [
                'name' => $permission,
                'display_name' => (new RouteNameParser)->parse($permission),
                'guard_name' => 'web'
            ]);

            foreach($this->attributes['roles'] as $role) {

                $role = $roles->find($role);

                // for user user can only assign user route
                if($permissionPrefix == 'user' && $role->type == RoleType::USER) {
                    $entry->assignRole($role);
                }

                // for admin user can assign both user and admin route
                if(($permissionPrefix == 'admin' || $permissionPrefix == 'user') && $role->type == RoleType::ADMIN) {
                    $entry->assignRole($role);
                }

                // for not user/admin permission prefix
                if($permissionPrefix != 'user' && $permissionPrefix != 'admin') {
                    $entry->assignRole($role);
                }
            	
            }
    	}
    	
    }

}