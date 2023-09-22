<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class SearchableGenerator
{
	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var string $foreignFieldTypes
     */
    protected $foreignFieldTypes;

    /**
     * @var string $foreignFieldSuffix
     */
	protected $foreignFieldSuffix;

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
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Repository/Searchable';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating searchable fields for repository
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param string $foreignFieldTypes
	 * @param string $foreignFieldSuffix
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generate(
		array $attributes, 
		array $moduleReplacements, 
		$foreignFieldTypes,
		$foreignFieldSuffix,
		$counter
	) {
		$this->foreignFieldTypes = $foreignFieldTypes;
		$this->foreignFieldSuffix = $foreignFieldSuffix;

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
	 * Handle generating shortcode with value
	 * 
	 * @param array $attributes
	 * 
	 * @return array
	 */
	protected function replacements($attributes) 
	{
		return [
			'$SEARCHABLE_FIELD_NAME$' => $this->getFieldName($attributes)
		];
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

		if(in_array($type, $this->foreignFieldTypes)) {
			return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column') . $this->foreignFieldSuffix;
		}
		
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column');
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
}