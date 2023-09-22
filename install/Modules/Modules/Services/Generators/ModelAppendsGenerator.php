<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\UploadFieldTypes;

class ModelAppendsGenerator
{
	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var array $templates
     */
	protected $templates = [
		'first' => 'first-field.stub',
		'next' => 'field.stub'
	];

	/**
	 * @var string $stubsPath
	 */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Appends';

	/**
	 * @var array $fieldTypesWithAppends
	 */
	protected $fieldTypesWithAppends = [
		'radio', 
		'select'
	];

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating model appends property
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generate(array $attributes, array $moduleReplacements, $counter) 
	{
		$type = $this->getFieldType($attributes);

		if(!in_array($type, UploadFieldTypes::lists())) {
			return '';
		}

		$template = $counter==0 
				? $this->templates['first']
				: $this->templates['next'];

		$path = base_path() . $this->stubsPath . '/' . $template;

		$content = $this->filesystem->get($path);

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
	}

	/**
	 * Handle generating replacements with shortcodes
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function replacements($attributes) 
	{
		$type = $this->getFieldType($attributes);

		$field = $this->getFieldName($attributes);

		if(in_array($type, $this->fieldTypesWithAppends)) {
			$field .= '_display';
		}

		return [
			'$FILLABLE_FIELD_NAME$' => $field
		];
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