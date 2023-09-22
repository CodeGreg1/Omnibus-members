<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TableColumnType {

	/**
	 * @var string $table
	 */
	protected $table;

	/**
	 * @var string $column
	 */
	protected $column;

	/**
	 * @param string $table
	 * @param string $table
	 */
	public function __construct($table, $column) 
	{
		$this->table = $table;
		$this->column = $column;
	}

	/**
	 * Handle on getting table columns
	 * 
	 * @return string
	 */
	public function get() 
	{
		$result = (new DescribeTable($this->table))->get();

		foreach($result as $entry) {
			if($entry->Field == $this->column) {
				return $this->type($entry->Type);
			}
		}
	}

	/**
	 * Parse the table column type so that we can determine what is it
	 * 
	 * @param string $type
	 * 
	 * @return string
	 */
	protected function type($type) 
	{
		$array = explode(' ', $type);
		$type = reset($array);
		$type = str_replace('(', ' ', $type);
		$type = str_replace(')', '', $type);

		return explode(' ', $type);
	}

}