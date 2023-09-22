<?php

namespace Modules\DashboardWidgets\Services\Helper;

use Illuminate\Support\Str;

class WidgetHelper {

	/**
	 * @var string $separator
	 */
	protected $separator = '|';

	/**
	 * @var string $chartSuffixSelectorName
	 */
	protected $chartSuffixSelectorName = '-chart';

	/**
	 * @var array $formatTypes
	 */
	public static $formatTypes = [
		'html' => 'HTML',
		'timeago' => 'Timeago',
		'date' => 'Date',
		'datetime' => 'Datetime'
	];

	/**
	 * Generate selector as kebab
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	public function generateSelector($attributes) 
	{
		return Str::kebab(strtolower($attributes['name'])) . $this->chartSuffixSelectorName;
	}

	/**
	 * Get the last value of the selected
	 * 
	 * @param string $attribute
	 * 
	 * @return string
	 */
	public function getLastValue($attribute) 
	{
		$array = explode($this->separator, $attribute);

		return end($array);
	}

	/**
	 * Get the first value
	 * 
	 * @param string $attribute
	 * 
	 * @return string
	 */
	public function getFirstValue($attribute) 
	{
		$array = explode($this->separator, $attribute);

		return strtolower(reset($array));
	}

}