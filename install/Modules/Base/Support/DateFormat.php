<?php

namespace Modules\Base\Support;

use DateTime;
use DateTimeZone;

class DateFormat
{   
    /**
     * List of available date formats
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            'M j, Y h:i A' => 'Readable Format ('.self::formatedDate().')',
            'Y-m-d' => 'Y-m-d',
            'd-m-Y' => 'd-m-Y',
            // 'd/m/Y' => 'd/m/Y', incorrect format when parsing 
            // 'm-d-Y' => 'm-d-Y', incorrect format when parsing 
            'm/d/Y' => 'm/d/Y'
        ];
    }

    /**
     * Get the formated date
     * 
     * @return string
     */
    protected static function formatedDate() 
    {
        $timestamp = time();
        $datetime = new DateTime("now", new DateTimeZone(auth()->user()->timezone));
        $datetime->setTimestamp($timestamp);
        return $datetime->format('M j, Y h:i A');
    }
}