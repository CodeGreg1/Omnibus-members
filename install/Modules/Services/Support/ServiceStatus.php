<?php

namespace Modules\Services\Support;

class ServiceStatus
{   
    /**
     * @var string VISIBLE
     */
    const VISIBLE = 'visible';

    /**
     * @var string HIDDEN
     */
    const HIDDEN = 'hidden';

    /**
     * Handle on listing ticket status
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            self::VISIBLE => [
                'name' => 'Visible',
                'color' => 'success'
            ],  
            self::HIDDEN => [
                'name' => 'Hidden',
                'color' => 'danger'
            ]
        ];
    }

    /**
     * Get ticket status
     * 
     * @param string $status
     * 
     * @return array|null
     */
    public static function get($status) 
    {
        return self::lists()[$status] ?? null;
    }

    /**
     * Get ticket status name
     * 
     * @param string $status
     * 
     * @return string|null
     */
    public static function name($status) 
    {
        return self::get($status)['name'] ?? null;
    }

    /**
     * Get ticket status color
     * 
     * @param string $status
     * 
     * @return string|null
     */
    public static function color($status) 
    {
        return self::get($status)['color'] ?? null;
    }
}