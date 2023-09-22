<?php

use Illuminate\Support\Carbon;

if (!function_exists('timeago')) {
    /**
     * Convert datetime from model field result to timeago and use user date format for formating the datetime based on the user
     * 
     * 
     * @return string|N/A
     */
    function timeago($datetime)
    {
    	if(!is_null($datetime) || $datetime != "") {
    		return Carbon::parse(user_date_format($datetime))
    		->diffForHumans();
    	}
    	
    	return 'N/A';
    }
}