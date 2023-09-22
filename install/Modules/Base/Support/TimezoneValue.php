<?php

namespace Modules\Base\Support;

use Illuminate\Support\Arr;
use Camroncade\Timezone\Facades\Timezone;

class TimezoneValue
{   
	/**
	 * Get the timezone value
	 * 
	 * @param string $timezone - The value must contain both key and value and separated with |
	 * 
	 * @return string
	 */
	public function get($timezone) 
	{
		$array = explode('|', $timezone);

		$timezones = Timezone::getTimezones();

		foreach($timezones as $key => $value) {
			if(Arr::first($array) == $key && Arr::last($array) == $value) {
				return $value;
			}
		}
	}
}