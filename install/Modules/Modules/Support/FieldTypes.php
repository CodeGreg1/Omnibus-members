<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class FieldTypes {

    /**
     * Lists of all field types
     * 
     * @return array
     */
	public static function lists()
    {
        return [
            'text',
            'email',
            'textarea',
            'password',
            'radio',
            'select',
            'checkbox',
            'number',
            'float',
            'money',
            'date',
            'datetime',
            'time',
            'file',
            'photo',
            'belongsToRelationship',
            'belongsToManyRelationship'
        ];
    }

}