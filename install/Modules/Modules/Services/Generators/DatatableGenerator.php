<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\ForeignFieldTypes;
use Modules\Modules\Support\UploadFieldTypes;

class DatatableGenerator
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
	protected $template = 'columns.stub';

	/**
	 * @var string $templateCheckboxElement
	 */
	protected $templateCheckboxElement = 'checkboxFormDataElement.stub';

	/**
	 * @var string $stubsPath
	 */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Datatable';

	/**
	 * @var array $fieldTypesWithDisplay
	 */
	protected $fieldTypesWithDisplay = [
		'radio', 
		'select'
	];

	/**
	 * @var array $extraFieldTypeUnsearchable
	 */
	protected $extraFieldTypeUnsearchable = [
		'checkbox'
	];

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating datatable template
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generate(array $attributes, array $moduleReplacements) 
	{
		$path = base_path() . $this->stubsPath . '/' . $this->template;

		$content = $this->filesystem->get($path);

		$field = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');

		$additionalFields = (new ModuleGeneratorHelper)->getMultipleValueByKey($attributes, 'additional_fields');

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->replacements($attributes, $moduleReplacements),
			$moduleReplacements
		), $content);

		// For foreign additional show in list columns
		if(count($additionalFields) && $field == ForeignFieldTypes::BELONGS_TO) {
			foreach($additionalFields as $additionalField) {
				$additionalFieldContent = $this->filesystem->get($path);

				$fieldName = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column');

				$title = str_replace('_', ' ', $additionalField);
				$title = str_replace('.', ' ', $title);
				$title = str_replace('-', ' ', $title);

				$content .= "
            {
                title: app.trans('".Str::title(ucfirst($title))."'),
                key: '".$fieldName.".".Str::snake($additionalField)."',
                classes: '".$moduleReplacements['$LOWER_NAME$']."-".Str::snake($additionalField)."-column',
                element: function(row) {
                	if(row.".$fieldName." !== null) {
                		return $('<span>'+row.".$fieldName.".".Str::snake($additionalField)."+'</span>');
                	}

                	return $('<span>&nbsp;</span>');
                }
            },";
			}
		}

		return $content;
	}

	/**
	 * Handle generating checkbox data element
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	protected function getCheckboxDataElementGenerator($attributes, $moduleReplacements) 
	{
		$fieldType = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');
		
		if($fieldType != 'checkbox') {
			return '';
		}

		$path = base_path() . $this->stubsPath . '/' . $this->templateCheckboxElement;

		$content = $this->filesystem->get($path);

		$content = (new ModuleGeneratorHelper)->replace(array_merge(
			$this->checkboxDataElementReplacements($attributes),
			$moduleReplacements
		), $content);

		return $content;
	}

	/**
	 * Handle shortcode replacements
	 * 
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	protected function replacements($attributes, $moduleReplacements) 
	{
		return [
			'$DATATABLE_ID_FIELD_CLASS$' => $this->getFieldIdAdditionalClass($attributes),
			'$DATATABLE_FIELD_NAME$' => $this->getFieldName($attributes),
			'$DATATABLE_FIELD_SHOW$' => $this->getFieldShow($attributes),
			'$DATATABLE_FIELD_SEARCHABLE$' => $this->getFieldSearchable($attributes),
			'$DATATABLE_FIELD_NAME_SNAKE$' => $this->getFieldNameSnake($attributes),
			'$DATATABLE_FIELD_VISUAL$' => $this->getFieldVisual($attributes),
			'$CHECKBOX_DATA_ELEMENT$' => $this->getCheckboxDataElementGenerator($attributes, $moduleReplacements),
			'$DATATABLE_FOREIGN_FIELD_NAME$' => $this->foreignFieldHtml($attributes),
			'$DATATABLE_UPLOAD_FIELD_NAME$' => $this->uploadFieldHtml($attributes),
		];
	}

	/**
	 * Handle checkbox data element replacements
	 * 
	 * @param array $attributes
	 * 
	 * @return array
	 */
	protected function checkboxDataElementReplacements($attributes) 
	{
		return [
			'$DATATABLE_FIELD_NAME$' => $this->getFieldName($attributes),
			'$DATATABLE_FIELD_NAME_SNAKE$' => $this->getFieldNameSnake($attributes),
			'$DATATABLE_FIELD_VISUAL$' => $this->getFieldVisual($attributes)
		];
	}

	/**
	 * Handle generating foreign field in HTML format
	 * 
	 * @param array $attributes
	 * 
	 * @return string 
	 */
	protected function foreignFieldHtml($attributes) 
	{
		$field = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');

		if($field == ForeignFieldTypes::BELONGS_TO) {

			$fieldShow = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_show');

			return ",
                element: function(row) {
                	if(row.".$this->getFieldName($attributes)." !== null) {
                    	return $('<span>'+row.".$this->getFieldName($attributes).".".$fieldShow."+'</span>');
                    }

                    return $('<span>&nbsp;</span>');
                }";
		}

		if($field == ForeignFieldTypes::BELONGS_TO_MANY) {

			$fieldShow = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_show');

			return ",
                element: function(row) {
                	var html = '';
                    if(row.".$this->getFieldName($attributes).".length) {
                        $.each(row.".$this->getFieldName($attributes).", function(key, entry) {
                            html += '<span class=\"badge badge-info\">'+entry.".$fieldShow."+'</span>';
                        });
                        return $(html);
                    }

                    return $('<span>&nbsp;</span>');
                }";
		}
	}

	/**
	 * Handle on generating upload file field into HTML format
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function uploadFieldHtml($attributes) 
	{
		$field = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');

		if(in_array($field, UploadFieldTypes::lists())) {

			return ",
                element: function(row) {
                    var html = '<span>&nbsp;</span>';

                    if (row.".$this->getFieldName($attributes).".length) {

                        $.each(row.".$this->getFieldName($attributes).", function(key, entry) {
                            var src = entry.thumbnail;

                            if(entry.generated_conversions.length == 0 && app.baseImageType.lists.indexOf(entry.mime_type) !== -1) {
                                src = entry.url;
                            }

                            html += '<a href=\"'+entry.url+'\" target=\"_blank\" title=\"'+entry.file_name+'\">';
                            html += '<img src=\"'+src+'\" class=\"img-thumbnail thumbnail-size\">';
                            html += '</a>';
                        });

                    }

                    return $(html);
                }";
		}
	}

	/**
	 * Handle generating field ID additional class name
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldIdAdditionalClass($attributes) 
	{
		$field = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column');

		if($field == 'id') {
			return ' tb-id-column';
		}
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
		$field = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_database_column');

		return $field;
	}

	/**
	 * Handle generating field show
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	public function getFieldShow($attributes) 
	{
		$fieldShow = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_show');
		
		if($fieldShow != "") {
			return '.' . $fieldShow;
		}
	}

	/**
	 * Handle on genrating searchable fields
	 * 
	 * @param array $attributes
	 * 
	 * @return string
	 */
	protected function getFieldSearchable($attributes) 
	{
		$type = (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');

		$unSearchableTypes = array_merge($this->fieldTypesWithDisplay, UploadFieldTypes::lists(), $this->extraFieldTypeUnsearchable);

		if(in_array($type, $unSearchableTypes)) {
			return 'false';
		}

		return 'true';
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
	 * Handle generating field name visual format
	 * 
	 * @param array $attributes
	 * 
	 * @return array
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
	 * @return array
	 */
	protected function getFieldType($attributes) 
	{
		return (new ModuleGeneratorHelper)->getValueByKey($attributes, 'field_type');
	}
}