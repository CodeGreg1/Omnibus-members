<?php

namespace Modules\Modules\Services\Generators\Form;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\ForeignFieldTypes;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class Form {

	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var string $stubsPath
     */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Form/Stubs';

	/**
	 * @var array $templates
	 */
	protected $templates = [
		'create' => [
			'text' => 'text.create.stub',
			'email' => 'email.create.stub',
			'textarea' => 'textarea.create.stub',
			'password' => 'password.create.stub',
			'radio' => 'radio.create.stub',
			'select' => 'select.create.stub',
			'checkbox' => 'checkbox.create.stub',
			'number' => 'number.create.stub',
			'float' => 'float.create.stub',
			'money' => 'float.create.stub',
			'date' => 'text.create.stub',
			'datetime' => 'text.create.stub',
			'time' => 'text.create.stub',
			'belongsToRelationship' => 'belongsToRelationship.create.stub',
			'belongsToManyRelationship' => 'belongsToManyRelationship.create.stub',
			'photo' => 'photo.create.stub',
			'file' => 'file.create.stub'
		],
		'edit' => [
			'text' => 'text.edit.stub',
			'email' => 'email.edit.stub',
			'textarea' => 'textarea.edit.stub',
			'password' => 'password.edit.stub',
			'radio' => 'radio.edit.stub',
			'select' => 'select.edit.stub',
			'checkbox' => 'checkbox.edit.stub',
			'number' => 'number.edit.stub',
			'float' => 'float.edit.stub',
			'money' => 'float.edit.stub',
			'date' => 'text.edit.stub',
			'datetime' => 'text.edit.stub',
			'time' => 'text.edit.stub',
			'belongsToRelationship' => 'belongsToRelationship.edit.stub',
			'belongsToManyRelationship' => 'belongsToManyRelationship.edit.stub',
			'photo' => 'photo.edit.stub',
			'file' => 'file.edit.stub'
		]
	];

	/**
	 * @var string $datepickerClass
	 */
	protected $datepickerClass = 'datepicker';

	/**
	 * @var string $datetimepickerClass
	 */
	protected $datetimepickerClass = 'datetimepicker';

	/**
	 * @var string $timepickerClass
	 */
	protected $timepickerClass = 'timepicker';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating form fields
	 * 
	 * @param string $type
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generateField($type, $attributes, $moduleReplacements) 
	{	
		$inType = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'in_'.$type);
		if(isset($this->templates[$type]) && $inType == 'on') {
			foreach ($this->templates[$type] as $key => $stub) {

				$fieldType = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');

				if($fieldType == $key) {

					$path = base_path() . $this->stubsPath . '/' . $stub;

					$content = $this->filesystem->get($path);

					$content = $this->replace(array_merge(
						$this->replacements($attributes),
						$moduleReplacements
					), $content);

					return $content;

				}
			}
		}
		
	}

	/**
	 * Handle replacements as array
	 * 
	 * @param array $attributes
	 * 
	 * @return array
	 */
	public function replacements($attributes) 
	{
		$validation = $this->getFieldFormValidation($attributes);

		return [
			'$CHECKBOX_DEFAULT_VALUE$' => $this->getCheckboxDefaultValue($attributes),
			'$FORM_FIELD_DEFAULT_VALUE$' => $this->getFieldFormDefaultValue($attributes),
			'$FORM_FIELD_NAME$' => $this->getFieldFormName($attributes),
			'$FORM_FIELD_NAME_LOWER$' => $this->getFieldFormNameLower($attributes),
			'$FORM_FIELD_NAME_LOWER_VISUAL$' => $this->getFieldFormNameLowerVisual($attributes),
			'$FORM_FIELD_NAME_UPPER$' => $this->getFieldFormNameUpper($attributes),
			'$FORM_FIELD_TYPE_UPPER$' => $this->getFieldFormTypeUpper($attributes),
			'$FORM_FIELD_NAME_SNAKE$' => $this->getFieldFormNameSnake($attributes),
			'$FORM_FIELD_VISUAL$' => $this->getFieldFormVisual($attributes),
			'$FORM_FIELD_VALIDATION$' => $this->getValidationField($attributes, $validation['validation']),
			'$FORM_VALIDATION_VISUAL$' => $validation['visual'],
			'$FORM_TEXTAREA_TINYMCE_ID$' => $this->getTinyMCEId($attributes),
			'$FORM_TEXTAREA_TINYMCE_CLASS$' => $this->getTinyMCEClass($attributes),
			'$FORM_CHECKBOX_OPTIONAL_ZERO_VALUE$' => $this->getCheckboxOptionalZeroValue($attributes),
			'$FORM_FIELD_FLOAT_MIN_MAX$' => $this->getFieldFormFloatMinxMax($attributes),
			'$FORM_FIELD_DATEPICKER_TYPE_CLASS$' => $this->getFieldFormDateTypeClass($attributes),
			'$FOREIGN_FORM_FIELD_NAME$' => $this->getForeignFieldFormName($attributes),
			'$BELONGS_TO_FOREIGN_FORM_FIELD_NAME$' => $this->getBelongsToForeignFieldFormName($attributes),
			'$FOREIGN_FORM_FIELD_LABEL_SELECT_OPTION$' => $this->getForeignFieldFormLabelSelectOption($attributes),
			'$FOREIGN_FORM_FIELD_VARIABLE_NAME$' => $this->getForeignFieldFormVariableName($attributes),
			'$FOREIGN_FORM_FIELD_PLACEHOLDER_NAME$' => $this->getForeignFieldFormPlaceholderName($attributes),
			'$FOREIGN_MODEL_RELATION_NAME$' => $this->getForeignModelRelationName($attributes),
			'$FORM_FIELD_HELP$' => $this->getFormFieldHelp($attributes)
		];
	}

	/**
	 * Handle generating form field help
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFormFieldHelp($attributes) 
	{
		$fieldTooltip = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_tooltip');

		if($fieldTooltip != "") {
			return "
                                    <small class='form-text text-muted form-help'>@lang('".$fieldTooltip."')</small>";
		}
	}

	/**
	 * Handle getting field form date type class
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormDateTypeClass($attributes) 
	{
		$type = $this->getFieldFormType($attributes);

		if($type == 'date') {
			return  ' '.$this->datepickerClass;
		}

		if($type == 'datetime') {
			return  ' '.$this->datetimepickerClass;
		}

		if($type == 'time') {
			return  ' '.$this->timepickerClass;
		}

		return '';
	}

	/**
	 * Handle getting field form float min and max
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormFloatMinxMax($attributes) 
	{
		$content = '';
		$type = $this->getFieldFormType($attributes);
		$min = $this->getFieldFormMin($attributes);
		$max = $this->getFieldFormMax($attributes);

		if($type != 'float') {
			return '';
		}

		if($min != '') {
			$content .= ' min="'.$min.'"';
		}

		if($max != '') {
			$content .= ' max="'.$max.'"';
		}

		return $content;
	}

	/**
	 * Handle getting checkbox default value
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getCheckboxDefaultValue($attributes) 
	{
		$defaultValue = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'default_value');

		return $defaultValue == 'checked' ? '
                                            checked' : '';
 	}

 	/**
 	 * Handle on getting checkbox optional with zero value
 	 * 
 	 * @param array $attributes
 	 * 
 	 * @return string
 	 */
 	protected function getCheckboxOptionalZeroValue($attributes) 
 	{
 		$validation = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_validation');

 		if($validation == 'optional') {
 			return '
                                        <input type="hidden" name="'.$this->getFieldFormName($attributes).'" value="0">';
 		}
 	}

 	/**
 	 * Handle getting validation field
 	 * 
 	 * @param array $attributes
 	 * @param string $validation
 	 * 
 	 * @return string
 	 */
	protected function getValidationField($attributes, $validation) 
	{
		$tinyMCE = (new ModuleGeneratorHelper)
			->getValueByKey($attributes, 'field_textarea_tinymce');

		if($tinyMCE == 'on') {
			return '';
		}

		return $validation;
	}

	/**
	 * Handle getting tinyMCE class
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getTinyMCEClass($attributes) 
	{
		$content = '';
		$tinyMCE = (new ModuleGeneratorHelper)
			->getValueByKey($attributes, 'field_textarea_tinymce');

		if($tinyMCE == 'on') {
			$content = ' $CRUD_LOWER_END_DASH$$KEBAB_NAME$-tinymce-default';
		}
		return $content;
	}

	/**
	 * Handle getting tinyMCE ID
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getTinyMCEId($attributes) 
	{
		$content = '';
		$tinyMCE = (new ModuleGeneratorHelper)
			->getValueByKey($attributes, 'field_textarea_tinymce');

		if($tinyMCE == 'on') {
			$field = (new ModuleGeneratorHelper)
				->getValueByKey($attributes, 'field_database_column');
			$content = '
                                        id="'.$field.'"';
		}
		return $content;
	}

	/**
	 * Handle on getting field form name with snake format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormNameSnake($attributes) 
	{
		$name = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column');

		return str_replace('_', '-', $name);
	}

	/**
	 * Handle on getting form field default value
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormDefaultValue($attributes) 
	{
		$defaultValue = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'default_value');

		if($defaultValue != '') {
			return ' 
                                        value="'.$defaultValue.'"';
		}

		return '';
 	}

 	/**
 	 * Handle on getting field form type
 	 * 
 	 * @param array $attributes
 	 * 
 	 * @return string
 	 */
 	protected function getFieldFormType($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');
	}

	/**
	 * Handle on getting field form min
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormMin($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'min_length');
	}

	/**
	 * Handle on getting field form max
	 * 
	 * @param array $string
	 * 
	 * @return string
	 */
	protected function getFieldFormMax($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'max_length');
	}

	/**
	 * Handle on getting field form type as upper type
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormTypeUpper($attributes) 
	{
		return strtoupper($this->getFieldFormType($attributes));
	}

	/**
	 * Handle on getting field form name
	 * 
	 * @param arrray $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormName($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column');
	}

	/**
	 * Handle on getting field form name with upper case
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormNameUpper($attributes) 
	{
		return strtoupper($this->getFieldFormName($attributes));
	}

	/**
	 * Handle on getting form field name with lower case
	 * 
	 * @return array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormNameLower($attributes) 
	{
		return strtolower($this->getFieldFormName($attributes));
	}

	/**
	 * Handle on getting form field name as lower case on visual
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormNameLowerVisual($attributes) 
	{
		return str_replace('_', ' ', strtolower($this->getFieldFormName($attributes)));
	}

	/**
	 * Handle on getting form field visuality
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldFormVisual($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_visual_title');
	}

	/**
	 * Handle on getting form field validation
	 * 
	 * @param array $attributes
	 * 
	 * @return array
	 */
	protected function getFieldFormValidation($attributes) 
	{
		$type = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_validation');

		return $type == 'optional' 
			? ['visual' => 'Optional',
				'validation' => ''
			] : ['visual' => 'Required',
				'validation' => ' required'
			];
	}

	/**
	 * Handle on shortcodes replacement
	 * 
	 * @param array $replacements
	 * @param string $content
	 * 
	 * @return string
	 */
	protected function replace($replacements, $content) 
    {   
        foreach($replacements as $shortcode=>$value) {
            $content = str_replace($shortcode, $value, $content);
        }
        return $content;
    }

    /**
     * Handle on getting form foreign field name
     * 
     * @param array $attributes
     * 
     * @return string
     */
    protected function getForeignFieldFormName($attributes) 
	{
		return Str::snake($this->getFieldFormName($attributes)) . '_id';
	}

	/**
     * Handle on getting form belongs to foreign field form name
     * 
     * @param array $attributes
     * 
     * @return string
     */
	protected function getBelongsToForeignFieldFormName($attributes) 
	{
		return Str::snake($this->getFieldFormName($attributes));
	}

	/**
     * Handle on getting form foreign field label select option
     * 
     * @param array $attributes
     * 
     * @return string
     */
	protected function getForeignFieldFormLabelSelectOption($attributes) 
	{
		return strtolower($this->getFieldFormVisual($attributes));
	}

	/**
     * Handle on getting form foreign field variable name
     * 
     * @param array $attributes
     * 
     * @return string
     */
	protected function getForeignFieldFormVariableName($attributes) 
	{	
		return lcfirst(Str::studly(Str::plural($this->getFieldFormName($attributes))));
	}

	/**
     * Handle on getting form foreign field placeholder name
     * 
     * @param array $attributes
     * 
     * @return string
     */
	protected function getForeignFieldFormPlaceholderName($attributes) 
	{	
		return strtolower($this->getFieldFormVisual($attributes));
	}

	/**
     * Handle on getting form foreign model relation name
     * 
     * @param array $attributes
     * 
     * @return string
     */
	protected function getForeignModelRelationName($attributes) 
	{
		return Str::plural(Str::camel($this->getFieldFormName($attributes)));
	}



}