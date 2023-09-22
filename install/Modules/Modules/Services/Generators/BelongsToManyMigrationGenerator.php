<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\TableName;
use Modules\Modules\Support\TableColumnType;
use Modules\Modules\Support\ModelByNamespace;
use Modules\Modules\Support\ForeignFieldTypes;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class BelongsToManyMigrationGenerator {

	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/BelongsToMany';

	/**
	 * @var string $template
	 */
	protected $template = 'migrations.stub';

	/**
	 * @var array $tableNames
	 */
	protected $tableNames = [];

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle on generating belongsToManyMigration
	 * 
	 * @param array $fields
	 * @param array $replacements
	 * 
	 * @return array
	 */
	public function generate($fields, $replacements) 
	{
		$result = '';

		foreach($fields as $fieldName => $fieldValue) {
			$field = json_decode($fieldValue,true);

			$type = (new ModuleGeneratorHelper)->getValueByKey($field, 'field_type');

			if($type == ForeignFieldTypes::BELONGS_TO_MANY) {
				$path = base_path() . $this->stubsPath . '/' . $this->template;

				$content = $this->filesystem->get($path);

				$result .= (new ModuleGeneratorHelper)->replace(array_merge(
					$this->replacements($field, $replacements),
					$replacements
				), $content);
			}
		}

		return [
			'result' => $result,
			'table_names' => $this->tableNames
		];
	}

	/**
	 * Handle generating table name
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * 
	 * @return string
	 */
	public function tableName($attributes, $replacements) 
	{	
		return Str::singular($this->fieldName($attributes)) . '_' . $replacements['$SINGULAR_LOWER_NAME$'];
	}

	/**
	 * Handle shortcodes replacement
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * 
	 * @return array
	 */
	protected function replacements($attributes, $replacements) 
	{
		$tableName = $this->tableName($attributes, $replacements);

		$this->tableNames[] = $tableName;

		return [
			'$BELONGS_TO_MANY_TABLE_NAME$' => $tableName,
			'$BELONGS_TO_MANY_FOREIGN_ID$' => $this->foregnId($attributes, $replacements),
			'$BELONGS_TO_MANY_TABLE_MODEL$' => $this->tableModel($attributes, $replacements),
			'$BELONGS_TO_MANY_FOREIGN_FK$' => $this->foregnFK($attributes, $replacements),
			'$BELONGS_TO_MANY_RELATED_ID$' => $this->relatedid($attributes, $replacements),
			'$BELONGS_TO_MANY_RELATED_UNSIGNED$' => $this->relatedUnsigned($attributes, $replacements),
			'$BELONGS_TO_MANY_RELATED_TABLE$' => $this->relatedTableName($attributes, $replacements),
			'$BELONGS_TO_MANY_RELATED_FK$' => $this->relatedFK($attributes, $replacements),
		];
	}

	/**
	 * Handle on generating field name
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function fieldName($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column');
	}

	/**
	 * Handle on generating foreign ID
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * 
	 * @return string
	 */
	public function foregnId($attributes, $replacements) 
	{
		return Str::singular($replacements['$TABLE_MODEL$']) . '_id';
	}

	/**
	 * Handle on generating foreign key field
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * 
	 * @return string
	 */
	protected function foregnFK($attributes, $replacements) 
	{
		return $replacements['$TABLE_MODEL$'] . '_fk_' . rand(1, 12347891);
	}

	/**
	 * Handle on getting table model
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * 
	 * @return string
	 */
	protected function tableModel($attributes, $replacements) 
	{
		return $replacements['$TABLE_MODEL$'];
	}

	/**
	 * Handle on getting related ID
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * 
	 * @return string
	 */
	public function relatedid($attributes, $replacements) 
	{
		return Str::singular($this->fieldName($attributes)) . '_id';
	}

	/**
	 * Handle on generating related unsigned field
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * 
	 * @return string
	 */
	protected function relatedUnsigned($attributes, $replacements) 
	{
		$namespace = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'related_model');

		$model = (new ModelByNamespace($namespace))->get(); 

		$table = (new TableName($model, $namespace))->get();

		$result = (new TableColumnType($table, 'id'))->get();

		$method = 'unsignedBigInteger';

		if(isset($result[0]) && $result[0] == 'int') {
			$method = 'unsignedInteger';
		}

		return '$'."table->".$method."('".$this->relatedid($attributes, $replacements)."');";
	}

	/**
	 * Handle on generating related table name
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * 
	 * @return string
	 */
	protected function relatedTableName($attributes, $replacements) 
	{
		$namespace = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'related_model');

		$model = (new ModelByNamespace($namespace))->get();
		return (new TableName($model, $namespace))->get();
	}
	
	/**
	 * Handle on generating related foreign key
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * 
	 * @return string
	 */
	protected function relatedFK($attributes, $replacements) 
	{
		return $this->relatedTableName($attributes, $replacements) . '_fk_' . rand(1, 12347891);
	}

}