<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\UploadFieldTypes;
use Modules\Modules\Support\ForeignFieldTypes;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class ViewFieldGenerator
{
	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var string $template
     */
	protected $template = 'field.stub';

	/**
     * @var string $belongsToManytemplate
     */
	protected $belongsToManytemplate = 'belongsToManyField.stub';

	/**
     * @var string $templateChoices
     */
	protected $templateChoices = 'choices.stub';

	/**
     * @var string $templateCheckbox
     */
	protected $templateCheckbox = 'checkbox.stub';

	/**
     * @var string $templateTinyMCE
     */
	protected $templateTinyMCE = 'fieldTinyMCE.stub';

	/**
     * @var string $templateUpload
     */
	protected $templateUpload = 'upload.stub';

	/**
     * @var array $choices
     */
	protected $choices = [
		'select',
		'radio'
	];

	/**
     * @var string $stubsPath
     */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/View';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating view fields
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generate(array $attributes, array $moduleReplacements) 
	{
		if(in_array($this->getFieldType($attributes), $this->choices)) {
			$path = base_path() . $this->stubsPath . '/' . $this->templateChoices;
		} elseif($this->getFieldType($attributes) == 'checkbox') {
			$path = base_path() . $this->stubsPath . '/' . $this->templateCheckbox;
		} elseif($this->getFieldType($attributes) == ForeignFieldTypes::BELONGS_TO_MANY) {
			$path = base_path() . $this->stubsPath . '/' . $this->belongsToManytemplate;
		} elseif(in_array($this->getFieldType($attributes), UploadFieldTypes::lists())) {
			$path = base_path() . $this->stubsPath . '/' . $this->templateUpload;
		} else {
			$template = $this->template;

			if($this->getFieldTextareaTinyMCE($attributes)  == 'on') {
				$template = $this->templateTinyMCE;
			}

			$path = base_path() . $this->stubsPath . '/' . $template;
		}

		$content = $this->filesystem->get($path);

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
	}

	/**
	 * Handle generating shortcodes with values
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function replacements($attributes) 
	{
		return [
			'$FORM_FIELD_VISUAL$' => $this->getFieldVisual($attributes),
			'$FORM_FIELD_NAME$' => $this->getFieldName($attributes),
			'$FORM_FIELD_NAME_UPPER$' => $this->getFieldFormNameUpper($attributes),
			'$FORM_FIELD_TYPE_UPPER$' => $this->getFieldFormTypeUpper($attributes),
			'$FOREIGN_FORM_FIELD_NAME$' => $this->getForeignFieldNameShow($attributes)
		];
	}

	/**
	 * Handle generating foreign field name display format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getForeignFieldNameShow($attributes) 
	{
		$type = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');

		if(in_array($type, ForeignFieldTypes::lists())) {
			return '->'.(new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_show');
		}
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
	 * Handle generating field type with upper case format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormTypeUpper($attributes) 
	{
		return strtoupper($this->getFieldType($attributes));
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
	 * Handle generating field form name with start upper case format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormNameUpper($attributes) 
	{
		return strtoupper($this->getFieldName($attributes));
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
	 * Handle generating textarea field name for tinymce
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldTextareaTinyMCE($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_textarea_tinymce');
	}
}