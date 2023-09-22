<?php

namespace Modules\Base\Support;

class TimeFormat
{   
    /**
     * List of time formats
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            12 => '12 hours',
            24 => '24 hours'
        ];
    }
}