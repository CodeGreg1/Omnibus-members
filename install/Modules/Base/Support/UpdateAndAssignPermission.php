<?php

namespace Modules\Base\Support;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Permission;

class UpdateAndAssignPermission {

	/**
	 * This function will able to save the permission and assign to the roles
	 * 
	 * @var string $old - The value of old permission
	 * @var string $new - The value of new permission
	 * @var array $roles - Optional | A list of roles inside the array
	 *  Example: [
	 * 	'Admin',
	 *  'User'
	 * ]
	 * 
	 * @return void
	 */
	public function execute($old, $new, $roles = []) 
	{	
		$permission = Permission::where('name', $old)->update([
			'name' => $new
		]);

		if(count($roles)) {
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

}