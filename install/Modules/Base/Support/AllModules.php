<?php

namespace Modules\Base\Support;

use Illuminate\Filesystem\Filesystem;

class AllModules
{	
	/**
	 * @var string $cacheName
	 */
	protected $cacheName = 'all_modules_path';

	/**
	 * @var int $hourCache
	 */
	protected $hourCache = 24;

	public function __construct() 
	{
		$this->file = new Filesystem;
	}

	/**
	 * Get all available modules path
	 * 
	 * @return array
	 */
	public function get() 
	{
		$path = base_path() . '/Modules/';

		if(!cache()->has($this->cacheName)) {
			cache()->add(
				$this->cacheName,
				json_encode($this->file->directories($path)), 
				now()->addHours($this->hourCache)
			);
		}
		
		return json_decode(cache()->get($this->cacheName),true);
	}

	/**
	 * Reset module chache
	 * 
	 * @return mixed
	 */
	public function resetCache() 
	{
		return cache()->forget($this->cacheName);
	}
}