<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\TableName;
use Illuminate\Support\Facades\Artisan;
use Modules\Modules\Support\TableColumnType;
use Modules\Modules\Support\ModelByNamespace;
use Modules\Modules\Services\Generators\Form\FormInterface;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class ForeignMigrationsGenerator
{
	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var string $fieldSuffix
     */
    protected $fieldSuffix = '_id';

    /**
     * @var array $templates
     */
	protected $templates = [
		'belongsToRelationship' => [
			'first' => [
				'required' => 'belongsToRelationship/first-required.stub',
				'optional' => 'belongsToRelationship/first-optional.stub'
			],
			'next' => [
				'required' => 'belongsToRelationship/required.stub',
				'optional' => 'belongsToRelationship/optional.stub'
			]
		],
	];

	/**
	 * @var string $stubsPath
	 */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Migrations';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating foriegn fields migration
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $counter
	 * 
	 * @return string
	 */
	public function generate(array $attributes, array $moduleReplacements, $counter) 
	{

		$type = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');

		$validation = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_validation');

		if(isset($this->templates[$type])) {

			$template = $counter==0 
				? $this->templates[$type]['first'][$validation]
				: $this->templates[$type]['next'][$validation];

			$path = base_path() . $this->stubsPath . '/' . $template;

			$content = $this->filesystem->get($path);

			$content = (new ModuleGeneratorHelper)->replace(array_merge(
				$this->replacements($attributes),
				$moduleReplacements
			), $content);

			return $content;

		}

		
	}

	/**
	 * Handle generating related table name
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	public function getRelatedTableName($attributes) 
	{
		$namespace = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'related_model');

		$model = (new ModelByNamespace($namespace))->get();
		return (new TableName($model, $namespace))->get();
	}

	/**
	 * Handle shortcodes replacment for foreign migration
	 * 
	 * @param array $attributes
	 * 
	 * @return array
	 */
	protected function replacements($attributes) 
	{
		return [
			'$MIGRATIONS_FIELD_NAME$' => $this->getFieldName($attributes) . $this->fieldSuffix,
			'$MIGRATIONS_FIELD_DEFAULT_VALUE$' => $this->getCheckboxDefaultValue($attributes),
			'$MIGRATIONS_FIELD_MAX_LENGTH$' => $this->getFieldMaxLength($attributes),
			'$MIGRATIONS_FIELD_NAME_SNAKE$' => $this->getFieldNameSnake($attributes),
			'$MIGRATIONS_FIELD_VISUAL$' => $this->getFieldVisual($attributes),
			'$MIGRATIONS_FIELD_FLOAT_LENGTH_AND_ACCURACY$' => $this->getFieldFloatLengthAndAccuracy($attributes),
			'$MIGRATIONS_RELATED_TABLE_NAME$' => $this->getRelatedTableName($attributes),
			'$MIGRATIONS_RELATED_PK_NAME$' => $this->getPKName($attributes),
			'$MIGRATIONS_FIELD_UNSIGNED$' => $this->getFieldUnsigned($attributes),
		];
	}

	/**
	 * Handle generating field type as unsignedInteger
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldUnsigned($attributes) 
	{
		$namespace = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'related_model');

		$model = (new ModelByNamespace($namespace))->get(); 

		$table = (new TableName($model, $namespace))->get();

		$result = (new TableColumnType($table, 'id'))->get();

		if(isset($result[0]) && $result[0] == 'int') {
			return 'unsignedInteger';
		}

		return 'unsignedBigInteger';
	}

	/**
	 * Handle generating foreign key name
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getPKName($attributes) 
	{
		return $this->getRelatedTableName($attributes) . '_fk_' . rand(1, 12347891);
	}

	/**
	 * Handle generating float field length and accurcy
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFloatLengthAndAccuracy($attributes) 
	{
		$floatLength = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'float_length');
		$floatAccuracy = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'float_accuracy');

		if($floatLength != '') {
			return ', '.$floatLength .', '.$floatAccuracy;
		}

		return ', 15, 2';//default
	}

	/**
	 * Handle generating checkbox default value
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getCheckboxDefaultValue($attributes) 
	{
		$defaultValue = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'default_value');

		return $defaultValue == 'checked' ? 1 : 0;
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
	 * Handle generating field max length
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldMaxLength($attributes) 
	{
		$maxLength = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'max_length');

		if($maxLength != '') {
			return ', '.$maxLength;
		}

		return '';
	}

	/**
	 * Handle generating field name as snake format
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
	 * Handle generating field visual label
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