<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ForeignFieldTypes {

    /**
     * @var string BELONGS_TO
     */
	const BELONGS_TO = 'belongsToRelationship';

    /**
     * @var string BELONGS_TO_MANY
     */
	const BELONGS_TO_MANY = 'belongsToManyRelationship';

    /**
     * Lists of foreign field types
     * 
     * @return array
     */
	public static function lists()
    {
        return [
            self::BELONGS_TO,
            self::BELONGS_TO_MANY
        ];
    }

}