<?php

namespace Modules\Tickets\Support;

class TicketPriority
{   
    /**
     * @var string LOW
     */
    const LOW = 'low';

    /**
     * @var string MEDIUM
     */
    const MEDIUM = 'medium';

    /**
     * @var string HIGH
     */
    const HIGH = 'high';

    /**
     * Handle on listing ticket status
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            self::LOW => [
                'name' => 'Low',
                'color' => 'info'
            ],  
            self::MEDIUM => [
                'name' => 'Medium',
                'color' => 'warning'
            ],
            self::HIGH => [
                'name' => 'High',
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