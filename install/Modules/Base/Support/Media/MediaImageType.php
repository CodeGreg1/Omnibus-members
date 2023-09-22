<?php

namespace Modules\Base\Support\Media;

class MediaImageType
{   

	/**
	 * We are using this for dropzone value for acceptedFiles
	 *
	 * @var string $lists
	 */
	public static $lists = '.jpeg,.jpg,.png,.gif';

	/**
	 * List of media image types allowed
	 * 
	 * @return array
	 */
    public static function lists()
    {
        return [
			'image/jpeg',
			'image/jpg',
			'image/png',
			'image/gif'
		];
    }
}