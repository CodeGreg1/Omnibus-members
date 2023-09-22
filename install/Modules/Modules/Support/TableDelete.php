<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Facades\DB;

class TableDelete {

	/**
	 * @var string $table
	 */
	protected $table;

	/**
	 * @param string $table
	 */
	public function __construct($table) 
	{
		$this->table = $table;
	}

	/**
	 * Handle on deleting table. This is useful when we rebuild/remove the module
	 * 
	 * @return void
	 */
	public function execute() 
	{
		DB::statement("SET FOREIGN_KEY_CHECKS = 0");
		DB::statement("DROP TABLE IF EXISTS $this->table");
		DB::statement("SET FOREIGN_KEY_CHECKS = 1");
	}

}