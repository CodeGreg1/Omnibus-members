<?php

namespace Modules\Base\Support;

class JsPolicy {

	/**
	 * @var array $defaultActions
	 */
	protected static $defaultActions = [
		'create',
		'show',
		'edit',
		'delete',
		'multi-delete',
		'restore',
		'force-delete',
		'remove-media'
	];

	/**
	 * @var array $permissions
	 */
	protected static $permissions;

	/**
	 * Get the module policies
	 * 
	 * @param string $moduleNameHandler - sample (user-management, users, email-templates) the handle is use in our route name as module name
	 * @param string $separator - we use this to separate the module name in our route name/permission
	 * @param array $actions = we use this as our action route
	 * 
	 * @return array
	 */
	public static function get($moduleNameHandle, $separator = '.', $actions = []) 
	{
		static::set($moduleNameHandle, $separator, $actions);

		return static::$permissions;
	}	

	/**
	 * Set and check authenticated user ability
	 * 
	 * @param string $moduleNameHandle
	 * @param string $separator
	 * @param array $actions
	 * 
	 * @return void
	 */
	protected static function set($moduleNameHandle, $separator = '.', $actions = []) 
	{
		$handles[] = $moduleNameHandle;

		foreach(CrudTypes::lists() as $crud) {
			$handles[] = $crud . '.' . $moduleNameHandle;
		}
		
		if(count($actions)) {
			self::$defaultActions = array_merge(self::$defaultActions, $actions);
		}

		foreach($handles as $handle) {
			foreach(self::$defaultActions as $action => $permission) {

				$permission = $handle . $separator . $permission;

				self::$permissions[$permission] = static::can($permission);
			}
		}
	}

	/**
	 * Check the user ability
	 * 
	 * @param string $permission
	 * 
	 * @return boolean
	 */
	protected static function can($permission) 
	{
		if(auth()->check()) {
			return auth()->user()->can($permission);
		}

		return false;
	}

}