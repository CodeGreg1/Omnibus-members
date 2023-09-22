<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class TableName {

	/**
	 * @var string $model
	 */
	protected $model;

	/**
	 * @var string $namespace
	 */
	protected $namespace;

	/**
	 * @param string $model
	 * @param string $namespace
	 */
	public function __construct($model, $namespace) 
	{
		$this->model = $model;
		$this->namespace = $namespace;
	}

	/**
	 * Handle getting table name using model with namespace
	 * 
	 * @return string
	 */
	public function get() 
	{
		$tableName = $this->default($this->model);

		if(method_exists($this->namespace, 'getTable')) {
			$tableName = (new $this->namespace)->getTable();
		}

		return $tableName;
	}

	/**
	 * Get default table name by model name
	 *
	 * @param string $model
	 *
	 * @return string
	 */
	protected function default($model) 
	{
		return  Str::plural(Str::snake($this->model));
	}
}