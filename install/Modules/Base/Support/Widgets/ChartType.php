<?php

namespace Modules\Base\Support\Widgets;

class ChartType
{      
    /**
     * List of chart types available
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            'line',
            'bar',
            'pie'
        ];
    }
}