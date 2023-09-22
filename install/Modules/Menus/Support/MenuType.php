<?php

namespace Modules\Menus\Support;

class MenuType
{   
    /**
     * @var string ADMIN
     */
    const ADMIN = 'Admin';

    /**
     * @var string USER
     */
    const USER = 'User';

    /**
     * @var string FRONTEND
     */
    const FRONTEND = 'Frontend';

    /**
     * List of menu types
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            self::ADMIN => self::ADMIN,
            self::USER => self::USER,
            self::FRONTEND => self::FRONTEND
        ];
    }
}