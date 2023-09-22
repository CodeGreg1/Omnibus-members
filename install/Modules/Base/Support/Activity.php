<?php

namespace Modules\Base\Support;

use App\Models\User;

class Activity
{	
	/**
	 * Generate user action log
	 * 
	 * @param string $logName
	 * @param string $event
	 * @param string|null $performedOn
	 * @param string $referenceName
	 * 
	 * @return void
	 */
	public static function log(
		$logName = 'default', 
		$event, 
		$performedOn = null, 
		$referenceName)
	{
		$causedBy = null;

		if(auth()->check()) {
			$causedBy = auth()->user();
		}

		if($event == LEAVE_IMPERSONATION) {
			$causedBy = self::forLeaveImpersonation();
		}
		
		$activity = activity()
		   ->event($event)
           ->useLog($logName)
           ->causedBy($causedBy);

       	if(!is_null($performedOn)) {
       		$activity->performedOn($performedOn);
       	}
           
           $activity->withProperties([
           		'user_agent' => request()->userAgent(),
           		'user_ip' => request()->ip()
           	])
           ->log(sprintf(
           		"%s %s%s", 
           		$event, $logName, $referenceName != '' 
           			? ' "'.$referenceName.'"'
           			: null
           	));
	}

	/**
	 * Handle for user leave impersonation
	 * 
	 * @return User
	 */
	protected static function forLeaveImpersonation() 
	{
		$manager = app('impersonate');
		$userId = $manager->getImpersonatorId();

		return User::findOrFail($userId);
	}
}