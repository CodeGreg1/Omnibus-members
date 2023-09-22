<?php

namespace Modules\Base\Support;

class CrudTypes
{	
	/**
     * @var string ADMIN
     */
	const ADMIN = 'admin';

    /**
     * @var string USER
     */
	const USER = 'user';

	/**
	 * @var array DEFAULT
	 */
	const DEFAULT = [
		'admin',
		'user'
	];

	/**
	 * List of crud types
	 * 
	 * @param array $appends
	 * 
	 * @return array
	 */
	public static function lists(array $appends = []) 
	{
		return array_merge(self::DEFAULT, $appends);
	}
}