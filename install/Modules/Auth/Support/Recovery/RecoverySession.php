<?php

namespace Modules\Auth\Support\Recovery;

trait RecoverySession
{
	protected $name = 'auth_with_recovery_code';

	public function getRecoverySession() 
	{
		return $this->name; 
	}

	public function setRecoverySession() 
	{
		session()->put($this->getRecoverySession(), true); 
	}

	public function forgetRecoverySession() 
	{
		if ( $this->hasRecoverySession() )
			session()->forget($this->getRecoverySession()); 
	}

	public function hasRecoverySession() 
	{
		return session()->has($this->getRecoverySession());
	}
}