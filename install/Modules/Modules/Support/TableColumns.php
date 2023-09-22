<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class TableColumns {

	/**
	 * Table model namespace
	 * 
	 * @var string $namespace
	 */
	protected $namespace;

	/**
	 * Determine if we will exclude the exclude lists
	 * 
	 * @var bool $withoutExcluded
	 */
	protected $withoutExcluded;

	/**
	 * Determine if we include the column data type
	 * 
	 * @var bool $withDataType
	 */
	protected $withDataType;

	/**
	 * @var array $excludes
	 */
	public $excludes = [
		'id',
		'created_at',
		'updated_at',
		'deleted_at',
		'password',
		'remember_token'
	];

	/**
	 * @var array EXCLUDED
	 */
	const EXCLUDED = [
		'id',
		'created_at',
		'updated_at',
		'deleted_at'
	];

	/**
	 * @param string $namespace
	 * @param bool $withoutExcluded
	 * @param bool $withDataType
	 */
	public function __construct($namespace, $withoutExcluded = false, $withDataType = false) 
	{
		$this->namespace = $namespace;
		$this->withoutExcluded = $withoutExcluded;
		$this->withDataType = $withDataType;
	}

	/**
	 * @return array
	 * 
	 * If without data type ['column' => 'Column']
	 * If with data type ['column' => ['display' => 'Column', 'type' => 'varchar']]
	 */
	public function get() 
	{
		$model = $this->modelName();
		
		$tableName = (new TableName($model, $this->namespace))->get();

		// exclude columns for users on demo
		if(env('APP_DEMO')) {
			$this->excludes = array_merge($this->excludes, [
				'authy_country_code',
				'authy_id',
				'authy_phone',
				'authy_status',
				'timezone_display'
			]);
		}

		if(Schema::hasTable($tableName)) {
			$columns = Schema::getColumnListing($tableName);

			if($this->withoutExcluded === false) {
				$columns = array_diff($columns, $this->excludes);
			}

			$cols = [];
			foreach($columns as $column) {
				if($this->withoutExcluded === true) {
					$cols[$column] = [
						'display' => str_replace('_', ' ', Str::title($column)),
						'type' => Schema::getColumnType($tableName, $column)
					];
				} else {
					$cols[$column] = str_replace('_', ' ', Str::title($column));
				}
			}

			return $cols;
		}
	}

	/**
	 * Get the model name using namespace
	 * 
	 * @return string
	 */
	protected function modelName() 
	{
		$models = explode('\\', $this->namespace);

		return end($models);
	}



}