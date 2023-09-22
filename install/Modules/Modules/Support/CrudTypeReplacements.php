<?php

namespace Modules\Modules\Support;

class CrudTypeReplacements
{
    /**
     * Define the lists of crud type shortcodes and values
     * 
     * @return array
     */
	public static function lists($type = '') 
	{
		$crudTypes = [];

        $crudTypes['admin']['$CRUD_STUDLY$'] = 'Admin';
        $crudTypes['admin']['$CRUD_LOWER$'] = 'admin';
        $crudTypes['admin']['$CRUD_UPPER$'] = 'ADMIN';
        $crudTypes['admin']['$CRUD_STUDLY_START_SLASH$'] = '\Admin';
        $crudTypes['admin']['$CRUD_LOWER_END_DOT$'] = 'admin.';
        $crudTypes['admin']['$CRUD_LOWER_END_SLASH$'] = 'admin/';
        $crudTypes['admin']['$CRUD_LOWER_END_DASH$'] = 'admin-';

        $crudTypes['user']['$CRUD_STUDLY$'] = 'User';
        $crudTypes['user']['$CRUD_LOWER$'] = 'user';
        $crudTypes['user']['$CRUD_UPPER$'] = 'USER';
        $crudTypes['user']['$CRUD_STUDLY_START_SLASH$'] = '\User';
        $crudTypes['user']['$CRUD_LOWER_END_DOT$'] = 'user.';
        $crudTypes['user']['$CRUD_LOWER_END_SLASH$'] = 'user/';
        $crudTypes['user']['$CRUD_LOWER_END_DASH$'] = 'user-';

        return $type != '' && isset($crudTypes[$type]) ? $crudTypes[$type] : [] ; 
	}
}