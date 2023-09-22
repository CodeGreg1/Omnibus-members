<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ModelByNamespace {

	/**
	 * @var string $namespace
	 */
	protected $namespace;

	/**
	 * @param string $namespace
	 */
	public function __construct($namespace) 
	{
		$this->namespace = $namespace;
	}

	/**
	 * Parse model namespace
	 * 
	 * @return string
	 */
	public function get() 
	{
		$models = explode('\\', $this->namespace);

		return end($models);
	}

}