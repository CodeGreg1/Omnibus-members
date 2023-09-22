<?php

namespace Modules\Base\Support\Media;

use Illuminate\Database\Eloquent\Model;

trait MediaHelper {

	/**
	 * @var string $defaultFileThumb
	 */
	protected $defaultFileThumb = '/upload/media/default/file-thumb.png';

	/**
	 * @var string $defaultFilePreview
	 */
	protected $defaultFilePreview = '/upload/media/default/file-preview.png';

	/**
	 * @var array $imageTypes
	 */
	public $imageTypes = [
		'image/jpeg',
		'image/jpg',
		'image/png',
		'image/gif'
	];

	/**
	 * Generate media urls
	 * 
	 * @param Model $model
	 * 
	 * @return Model
	 */
	public function mediaUrls($model) 
	{
		$model->url       = $model->getUrl();

        $model->thumbnail = (in_array($model->mime_type, $this->imageTypes)) 
        	? $model->getUrl('thumb') 
        	: $this->defaultFileThumb;

        $model->preview   = (in_array($model->mime_type, $this->imageTypes)) 
        	? $model->getUrl('preview') 
        	: $this->defaultFilePreview;

        return $model;
	}
}