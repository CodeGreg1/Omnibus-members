<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\ForeignFieldTypes;

class FillableGenerator
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
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Fillable';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating model fillable values
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
	 * Handle fillable shortcode replacements
	 * 
	 * @param array $attributes
	 * 
	 * @return array
	 */
	protected function replacements($attributes) 
	{
		return [
			'$FILLABLE_FIELD_NAME$' => $this->getFieldName($attributes)
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
		$type = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');

		if($type == ForeignFieldTypes::BELONGS_TO) {
			return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column') . $this->foreignFieldSuffix;
		}
		
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column');
	}

	/**
	 * Handle generating field name as visual format
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