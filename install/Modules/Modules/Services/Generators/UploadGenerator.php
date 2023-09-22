<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Base\Support\Media\MediaImageType;

class UploadGenerator
{
	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var string $methodTemplate
     */
	protected $methodTemplate = 'method.stub';

	/**
     * @var string $stubsPath
     */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Upload';

	/**
     * @var string $photo
     */
	protected $photo = 'photo';

	/**
     * @var array $uploads
     */
	protected $uploads = ['photo', 'file'];

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating upload method for controller
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generateMethod(array $attributes, array $moduleReplacements, $counter) 
	{
		$type = $this->getFieldType($attributes);

		if(!in_array($type, $this->uploads)) {
			return '';
		}

		$path = base_path() . $this->stubsPath . '/' . $this->methodTemplate;

		$content = $this->filesystem->get($path);

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
	}

	/**
	 * Handle generating call method
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generateCallMethod(array $attributes, array $moduleReplacements, $counter) 
	{
		$type = $this->getFieldType($attributes);

		if(!in_array($type, $this->uploads)) {
			return '';
		}

		$content = $this->getUploadCallMethod($attributes);

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
	}

	/**
	 * Handle generating controller method for media
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generateControllerMethodMedia(array $attributes, array $moduleReplacements, $counter) 
	{
		$type = $this->getFieldType($attributes);

		if(!in_array($type, $this->uploads)) {
			return '';
		}

		$content = "

        if (".'$'."request->hasFile('".'$'."FIELD_NAME$')) {

            foreach (".'$'."request->file('".'$'."FIELD_NAME$') as ".'$'."entry) {
                if (".'$'."entry->isValid()) {
                    ".'$'."model->addMedia(".'$'."entry)->toMediaCollection('".'$'."FIELD_NAME$');
                }
            }

        }";

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
	}

	/**
	 * Handle generating model media attributes
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generateModelMediaAttributes(array $attributes, array $moduleReplacements, $counter) 
	{
		$type = $this->getFieldType($attributes);

		if(!in_array($type, $this->uploads)) {
			return '';
		}

		$content = "

    public function get".'$'."FIELD_NAME_STUDLY".'$'."Attribute()
    {
        ".'$'."items = ".'$'."this->getMedia('".'$'."FIELD_NAME".'$'."');
        
        ".'$'."items->each(function (".'$'."item) {
            ".'$'."item = ".'$'."this->mediaUrls(".'$'."item);
        });

        return ".'$'."items;
    }";

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
	}

	/**
	 * Handle generating route
	 * 
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generateRoute($moduleReplacements) 
	{

		$content = "
        Route::delete('/remove-media', '".'$'."STUDLY_NAME".'$'."Controller@removeMedia')->name('".'$'."PLURAL_KEBAB_NAME".'$'.".remove-media');";

		$content = (new ModuleGeneratorHelper)->replace($moduleReplacements, $content);

		return $content;
        
	}

	/**
	 * Handle generating request import repository
	 * 
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generateRequestImportRepository($moduleReplacements) 
	{

		$content = '
use Modules\$STUDLY_NAME$\Repositories\$MODEL_PLURAL$Repository;';

		$content = (new ModuleGeneratorHelper)->replace($moduleReplacements, $content);

		return $content;
        
	}

	/**
	 * Handle generating repository with find method
	 * 
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generateRequestCallFindRepository($moduleReplacements) 
	{
		$content = '
        $repo = (new $MODEL_PLURAL$Repository)->find($this->id);';

        $content = (new ModuleGeneratorHelper)->replace($moduleReplacements, $content);

		return $content;
	}

	/**
	 * Handle generating form data script for create
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generateFormDataScriptCreate(array $attributes, array $moduleReplacements, $counter) 
	{
		$type = $this->getFieldType($attributes);

		if(!in_array($type, $this->uploads)) {
			return '';
		}

		$content = '

        var $FIELD_NAME$ = $(\'#$CRUD_LOWER_END_DASH$$KEBAB_NAME$-$FIELD_NAME$-dropzone\').get(0).dropzone.getAcceptedFiles();
        $.each($FIELD_NAME$, function (key, file) {
            data.append(\'$FIELD_NAME$[\' + key + \']\', $(\'#$CRUD_LOWER_END_DASH$$KEBAB_NAME$-$FIELD_NAME$-dropzone\')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
        });';

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
		
	}

	/**
	 * Handle generating validation for checking media total
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generateRequestCheckMediaTotal(array $attributes, array $moduleReplacements, $counter) 
	{
		$fieldValidation = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_validation');
		$type = $this->getFieldType($attributes);

		if(!in_array($type, $this->uploads)) {
			return '';
		}

		$content = '
        
        $$FIELD_NAME_CAMEL$Validation = \'nullable\';';

		if($fieldValidation == 'required') {
			$content .= '

        if(!is_null($repo) && $repo->$FIELD_NAME$->count() < 1) {
            $$FIELD_NAME_CAMEL$Validation = \'required\';
        }';
		}

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
	}

	/**
	 * Handle generating shortcodes with value
	 * 
	 * @param array $attributes
	 * 
	 * @return array
	 */
	protected function replacements($attributes) 
	{
		$type = $this->getFieldType($attributes);

		return [
			'$FIELD_NAME$' => $this->getFieldName($attributes),
			'$FIELD_NAME_STUDLY$' => $this->getFieldNameStudly($attributes),
			'$FIELD_NAME_CAMEL$' => $this->getFieldNameCamel($attributes),
			'$FIELD_TYPE_UPPER$' => $this->getFieldTypeUpper($attributes),
			'$FIELD_UPLOAD_MULTIPLE$' =>  $this->getUploadMultiple($attributes),
			'$FIELD_UPLOAD_MAX_FILES$' =>  $this->getUploadMaxFiles($attributes),
			'$FIELD_UPLOAD_PHOTO_EXT$' =>  $this->getUploadPhotoExtensions($attributes)
		];
	}

	/**
	 * Handle generating multiple upload
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getUploadMultiple($attributes) 
	{
		$multipleFiles = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'multiple_files');

		return $multipleFiles == 'on' ? 'true' : 'false';
	}

	/**
	 * Handle generating maximum files upload
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getUploadMaxFiles($attributes) 
	{
		$multipleFiles = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'multiple_files');

		return $multipleFiles == 'on' ? '10' : '1';
	}

	/**
	 * Handle generating photo extensions upload
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getUploadPhotoExtensions($attributes) 
	{
		$type = $this->getFieldType($attributes);

		if($type == $this->photo) {
			return "
            acceptedFiles: '". MediaImageType::$lists."',";
		}
	}

	/**
	 * Handle generating call method for upload
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getUploadCallMethod($attributes) 
	{
		return '
		app.$CRUD_LOWER$$STUDLY_NAME$Upload.$FIELD_NAME_CAMEL$();';
	}

	/**
	 * Handle generating field name with snake format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldNameSnake($attributes) 
	{
		return str_replace('_', '-', $this->getFieldName($attributes));
	}

	/**
	 * Handle generating field name
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldName($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column');
	}

	/**
	 * Handle generating field name with studly format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldNameStudly($attributes) 
	{
		$studyContent = Str::studly($this->getFieldName($attributes));
		$type = $this->getFieldType($attributes);

		return $studyContent;
	}

	/**
	 * Handle generating field name with camel format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldNameCamel($attributes) 
	{
		return Str::camel($this->getFieldNameStudly($attributes));
	}

	/**
	 * Handle generating field name with visual format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldVisual($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_visual_title');
	}

	/**
	 * Handle generating field type
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldType($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');
	}

	/**
	 * Handle generating field type upper case format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldTypeUpper($attributes) 
	{
		return strtoupper($this->getFieldType($attributes));
	}
}