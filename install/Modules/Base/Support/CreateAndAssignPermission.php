<?php

namespace Modules\Base\Support;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Permission;

class CreateAndAssignPermission {

	/**
	 * This function will able to save the permission and assign to the roles
	 * 
	 * @var array $permission - Permission details with name and display name
	 *  Example: [
	 * 	'name' => 'route.name', 
	 *  'display_name' => ' Route Name'
	 * ]
	 * @var array $roles - A list of roles inside the array
	 *  Example: [
	 * 	'Admin',
	 *  'User'
	 * ]
	 * 
	 * @return void
	 */
	public function execute(array $permission, array $roles) 
	{	
		$permission = Permission::updateOrCreate([
			'name' => $permission['name']
		], $permission);

		foreach($roles as $role) {
			$role = Role::where('name', $role)->first();

			if(!is_null($role)) {
				DB::table('role_has_permissions')->updateOrInsert([
					'permission_id' => $permission->id,
					'role_id' => $role->id
				], [
					'permission_id' => $permission->id,
					'role_id' => $role->id
				]);
			}
		}		
	}

}