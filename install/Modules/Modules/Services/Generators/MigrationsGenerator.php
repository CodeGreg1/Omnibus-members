<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\TableName;
use Illuminate\Support\Facades\Artisan;
use Modules\Modules\Support\TableColumns;
use Modules\Modules\Support\TableColumnType;
use Modules\Modules\Support\ModelByNamespace;
use Modules\Modules\Support\ForeignFieldTypes;
use Modules\Modules\Services\Generators\Form\FormInterface;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class MigrationsGenerator
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
		'text' => [
			'first' => [
				'required' => 'string/first-required.stub',
				'required_unique' => 'string/first-required-unique.stub',
				'optional' => 'string/first-optional.stub'
			],
			'next' => [
				'required' => 'string/required.stub',
				'required_unique' => 'string/required-unique.stub',
				'optional' => 'string/optional.stub'
			]
		],
		'email' => [
			'first' => [
				'required' => 'string/first-required.stub',
				'required_unique' => 'string/first-required-unique.stub',
				'optional' => 'string/first-optional.stub'
			],
			'next' => [
				'required' => 'string/required.stub',
				'required_unique' => 'string/required-unique.stub',
				'optional' => 'string/optional.stub'
			]
		],
		'textarea' => [
			'first' => [
				'required' => 'textarea/first-required.stub',
				'required_unique' => 'textarea/first-required-unique.stub',
				'optional' => 'textarea/first-optional.stub'
			],
			'next' => [
				'required' => 'textarea/required.stub',
				'required_unique' => 'textarea/required-unique.stub',
				'optional' => 'textarea/optional.stub'
			]
		],
		'password' => [
			'first' => [
				'required' => 'string/first-required.stub',
				'optional' => 'string/first-optional.stub'
			],
			'next' => [
				'required' => 'string/required.stub',
				'optional' => 'string/optional.stub'
			]
		],
		'radio' => [
			'first' => [
				'required' => 'string/first-required.stub',
				'optional' => 'string/first-optional.stub'
			],
			'next' => [
				'required' => 'string/required.stub',
				'optional' => 'string/optional.stub'
			]
		],
		'select' => [
			'first' => [
				'required' => 'string/first-required.stub',
				'optional' => 'string/first-optional.stub'
			],
			'next' => [
				'required' => 'string/required.stub',
				'optional' => 'string/optional.stub'
			]
		],
		'checkbox' => [
			'first' => [
				'required' => 'boolean/first-required.stub',
				'optional' => 'boolean/first-optional.stub'
			],
			'next' => [
				'required' => 'boolean/required.stub',
				'optional' => 'boolean/optional.stub'
			]
		],
		'number' => [
			'first' => [
				'required' => 'number/first-required.stub',
				'required_unique' => 'number/first-required-unique.stub',
				'optional' => 'number/first-optional.stub'
			],
			'next' => [
				'required' => 'number/required.stub',
				'required_unique' => 'number/required-unique.stub',
				'optional' => 'number/optional.stub'
			]
		],
		'float' => [
			'first' => [
				'required' => 'float/first-required.stub',
				'required_unique' => 'float/first-required-unique.stub',
				'optional' => 'float/first-optional.stub'
			],
			'next' => [
				'required' => 'float/required.stub',
				'required_unique' => 'float/required-unique.stub',
				'optional' => 'float/optional.stub'
			]
		],
		'money' => [
			'first' => [
				'required' => 'money/first-required.stub',
				'required_unique' => 'money/first-required-unique.stub',
				'optional' => 'money/first-optional.stub'
			],
			'next' => [
				'required' => 'money/required.stub',
				'required_unique' => 'money/required-unique.stub',
				'optional' => 'money/optional.stub'
			]
		],
		'date' => [
			'first' => [
				'required' => 'date/first-required.stub',
				'optional' => 'date/first-optional.stub'
			],
			'next' => [
				'required' => 'date/required.stub',
				'optional' => 'date/optional.stub'
			]
		],
		'datetime' => [
			'first' => [
				'required' => 'datetime/first-required.stub',
				'optional' => 'datetime/first-optional.stub'
			],
			'next' => [
				'required' => 'datetime/required.stub',
				'optional' => 'datetime/optional.stub'
			]
		],
		'time' => [
			'first' => [
				'required' => 'time/first-required.stub',
				'optional' => 'time/first-optional.stub'
			],
			'next' => [
				'required' => 'time/required.stub',
				'optional' => 'time/optional.stub'
			]
		]
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
	 * Handle generating module migrations
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * @param int $counter
	 * 
	 * @return string|null
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
	 * Handle on migration replacements with shortcode value
	 * 
	 * @param array $attributes
	 * 
	 * @return array
	 */
	protected function replacements($attributes) 
	{
		return [
			'$MIGRATIONS_FIELD_NAME$' => $this->getFieldName($attributes),
			'$MIGRATIONS_FIELD_DEFAULT_VALUE$' => $this->getCheckboxDefaultValue($attributes),
			'$MIGRATIONS_FIELD_MAX_LENGTH$' => $this->getFieldMaxLength($attributes),
			'$MIGRATIONS_FIELD_NAME_SNAKE$' => $this->getFieldNameSnake($attributes),
			'$MIGRATIONS_FIELD_VISUAL$' => $this->getFieldVisual($attributes),
			'$MIGRATIONS_FIELD_FLOAT_LENGTH_AND_ACCURACY$' => $this->getFieldFloatLengthAndAccuracy($attributes),
		];
	}

	/**
	 * Handle generating float field with length and accuracy
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
	 * Handle on generating checkbox default value
	 * 
	 * @param array $attributes
	 * 
	 * @return boolean
	 */
	protected function getCheckboxDefaultValue($attributes) 
	{
		$defaultValue = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'default_value');

		return $defaultValue == 'checked' ? 1 : 0;
 	}

 	/**
	 * Handle on generating fieldname
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
	 * Handle on generating field max length
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
	 * Handle on generating field name with snake format
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
	 * Handle on generating field visual format
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