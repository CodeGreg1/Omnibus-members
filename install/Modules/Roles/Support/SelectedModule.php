<?php

namespace Modules\Roles\Support;

class SelectedModule
{   
    /**
     * Modules that selected as default when creating role
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            'profile',
            'dashboard'
        ];
    }
}