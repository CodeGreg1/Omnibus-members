<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class ModelMutatorsGenerator
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
	protected $template = 'mutator-display.stub';

	/**
     * @var string $stubsPath
     */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/ModelMutators';

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
	 * Handle genearting model mutators
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

		if(!in_array($type, $this->fieldTypesWithAppends)) {
			return '';
		}

		$path = base_path() . $this->stubsPath . '/' . $this->template;

		$content = $this->filesystem->get($path);

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
	}

	/**
	 * Handle generating replacement with shortcode value
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function replacements($attributes) 
	{
		$type = $this->getFieldType($attributes);

		return [
			'$FIELD_NAME$' => $this->getFieldName($attributes),
			'$FIELD_NAME_STUDLY$' => $this->getFieldNameStudly($attributes),
			'$FIELD_NAME_UPPER$' => $this->getFieldNameUpper($attributes),
			'$FIELD_NAME_SPACED$' => $this->getFieldNameSpaced($attributes),
			'$FIELD_TYPE_UPPER$' => $this->getFieldTypeUpper($attributes)
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
	 * Handle generating field name with spaced format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldNameSpaced($attributes) 
	{
		$spaced = str_replace('_', ' ', $this->getFieldName($attributes));
		$type = $this->getFieldType($attributes);

		return $spaced;
	}

	/**
	 * Handle generating field name with upper case format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldNameUpper($attributes) 
	{
		return strtoupper($this->getFieldName($attributes));
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