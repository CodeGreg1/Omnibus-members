<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class UploadFieldTypes {

    /**
     * @var string PHOTO
     */
	const PHOTO = 'photo';

    /**
     * @var string FILE
     */
	const FILE = 'file';

    /**
     * List of default upload field types
     * 
     * @return array
     */
	public static function lists()
    {
        return [
            self::PHOTO,
            self::FILE
        ];
    }

}