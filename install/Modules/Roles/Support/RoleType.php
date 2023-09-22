<?php

namespace Modules\Roles\Support;

class RoleType
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
     * List of role types
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            self::ADMIN => self::ADMIN,
            self::USER => self::USER
        ];
    }
}