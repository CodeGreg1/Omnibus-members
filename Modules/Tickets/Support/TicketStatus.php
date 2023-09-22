<?php

namespace Modules\Tickets\Support;

class TicketStatus
{   
    /**
     * @var string OPEN
     */
    const OPEN = 'open';

    /**
     * @var string ONHOLD
     */
    const ONHOLD = 'onhold';

    /**
     * @var string CLOSED
     */
    const CLOSED = 'closed';

    /**
     * Handle on listing ticket status
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            self::OPEN => [
                'name' => 'Open',
                'color' => 'danger'
            ],  
            self::ONHOLD => [
                'name' => 'On Hold',
                'color' => 'warning'
            ],
            self::CLOSED => [
                'name' => 'Closed',
                'color' => 'default'
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