<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\UploadFieldTypes;
use Modules\Modules\Support\ForeignFieldTypes;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class RequestGenerator
{
	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var string $stubsPath
     */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Request';

	/**
	 * @var string $foreignFieldTypes
	 */
	protected $foreignFieldTypes;

	/**
	 * @var string $foreignFieldTypes
	 */
	protected $foreignFieldSuffix;

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating module request code
	 * 
	 * @param string $type
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param string $foreignFieldTypes
	 * @param string $foreignFieldSuffix
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generate(
		$type, 
		array $attributes, 
		array $moduleReplacements, 
		$foreignFieldTypes, 
		$foreignFieldSuffix, 
		$counter
	) {
		$fieldType = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');
		$inEdit = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'in_edit');
		$inCreate = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'in_create');
		$validation = $this->validation($attributes);

		$this->foreignFieldTypes = $foreignFieldTypes;

		$this->foreignFieldSuffix = $foreignFieldSuffix;

		// check if edit is not show on edit page
		if($type == 'edit' && $inEdit != 'on') {
			return '';
		}

		// check if edit is not show on edit page
		if($type == 'create' && $inCreate != 'on') {
			return '';
		}

		if($validation != '') {

			$template = $validation . '.stub';

			if($fieldType == ForeignFieldTypes::BELONGS_TO_MANY) {
				$template = $validation . '-' .ForeignFieldTypes::BELONGS_TO_MANY. '.stub';
			}

			// customize for upload edit
			if($type == 'edit' && in_array($fieldType, UploadFieldTypes::lists())) {
				$template = $validation . '-upload.stub';
			}

			$path = base_path() . $this->stubsPath . '/' . $template;

			$content = $this->filesystem->get($path);

			$content = (new ModuleGeneratorHelper)->replace(array_merge(
				$this->replacements($type, $attributes),
				$moduleReplacements
			), $content);

			if($counter == 0) {
				return str_replace('            
            ', '', $content);
			}
			
			return $content;
		}
	}

	/**
	 * Handle generating request validation
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function validation($attributes) 
	{
		$result = '';

		$validation = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_validation');
		$minLength = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'min_length');
		$maxLength = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'max_length');

		if($validation == 'optional') {
			$result .= 'nullable';
		}

		if($validation == 'required') {
			$result .= 'required';
		}

		if($validation == 'required_unique') {
			$result .= 'required_unique';
		}

		if($minLength != '') {
			if($result != '') {
				$result .= '-min';
			} else {
				$result .= 'min';
			}
		}

		if($maxLength != '') {
			if($result != '') {
				$result .= '-max';
			} else {
				$result .= 'max';
			}
		}

		return $result;
	}

	/**
	 * Handle generating shortcodes with values
	 * 
	 * @param string $type
	 * @param array $attributes
	 * 
	 * @return array
	 */
	protected function replacements($type, $attributes) 
	{
		return [
			'$FIELD_NAME_STUDLY$' => $this->getFieldNameStudly($attributes),
			'$FIELD_NAME_CAMEL$' => $this->getFieldNameCamel($attributes),
			'$VALIDATION_FIELD$' => $this->getFieldName($attributes),
			'$VALIDATION_EMAIL_TYPE$' => $this->getValidationEmailType($attributes),
			'$VALIDATION_INTEGER_TYPE$' => $this->getIntegerType($attributes),
			'$VALIDATION_FLOAT_TYPE$' => $this->getFloatType($attributes),
			'$VALIDATION_UPDATE_ID$' => $this->getValidationUpdateId($type, $attributes),
			'$MIN$' => $this->getMinValue($attributes),
			'$MAX$' => $this->getMaxValue($attributes),
			'$FIELD_UPLOAD_REQUIRED_VALIDATION$' => $this->getUploadRequiredValidation($attributes),
			'$FIELD_PHOTO_VALIDATION$' => $this->getPhotoUploadValidation($attributes)
		];
	}

	/**
	 * Handle generating photo upload validation
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getPhotoUploadValidation($attributes) 
	{
		$fieldType = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');
		$size = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'max_file_size');
		$maxWidthPx = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'max_width_px');
		$maxHeightPx = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'max_height_px');

		// for multiple photo upload
		if(UploadFieldTypes::PHOTO == $fieldType) {
			return "
            '".$this->getFieldName($attributes).".*' => 'image|mimes:jpeg,png,jpg,gif|max:" . $size * 1024 . "|dimensions:max_width=".$maxWidthPx.",max_height=".$maxHeightPx."',";
		}

		// for file photo upload
		if(UploadFieldTypes::FILE == $fieldType) {
			return "
            '".$this->getFieldName($attributes).".*' => 'max:" . $size * 1024 . "',";
		}
	}

	/**
	 * Handle generating upload required validation
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getUploadRequiredValidation($attributes) 
	{
		return '$'.$this->getFieldNameCamel($attributes).'Validation';
	}

	/**
	 * Handle generating validation get getting update id
	 * 
	 * @param string $type
	 * @param array $attributes
	 * 
	 * @return string
	 */
	public function getValidationUpdateId($type, $attributes) 
	{
		if($type == 'edit') {
			return ",' . " . "request()->route('id')";
		}

		return "'";
	}

	/**
	 * Handle generating email type validation
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getValidationEmailType($attributes) 
	{
		$type = $this->getFieldType($attributes);

		if($type == 'email') {
			return '|email';
		}

		return '';
	}

	/**
	 * Handle generating integer type validation
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getIntegerType($attributes) 
	{
		$type = $this->getFieldType($attributes);

		if($type == 'number') {
			return '|integer|min:-2147483648|max:2147483647';
		}

		return '';
	}

	/**
	 * Handle generating float type validation
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFloatType($attributes) 
	{
		$type = $this->getFieldType($attributes);

		if($type == 'float') {
			return '|numeric';
		}

		return '';
	}

	/**
	 * Handle generating min length validation
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getMinValue($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'min_length');
	}

	/**
	 * Handle generating max value validation
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getMaxValue($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'max_length');
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
		$type = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');

		if($type == ForeignFieldTypes::BELONGS_TO) {
			return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column') . $this->foreignFieldSuffix;
		}

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

}