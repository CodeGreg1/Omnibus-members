<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class UpdateOrCreateFieldsGenerator
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
		'last' => 'last-field.stub',
		'next' => 'field.stub'
	];

	/**
     * @var string $stubsPath
     */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Controller';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating updateOrCreate fields
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $totalFields
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generate(array $attributes, array $moduleReplacements, $totalFields, $counter) 
	{
		$counter = ($counter+1);

		$template = $totalFields==$counter
				? $this->templates['last']
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
			'$UPDATE_OR_CREATE_FIELD_NAME$' => $this->getFieldName($attributes)
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