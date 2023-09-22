<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DescribeTable {

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
	 * Handle getting table columns using describe command
	 * 
	 * @return Illuminate\Support\Facades\DB
	 */
	public function get() 
	{
		return DB::select('DESCRIBE ' . $this->table);
	}

}