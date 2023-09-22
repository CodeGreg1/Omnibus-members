<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\TableColumns;
use Modules\Modules\Support\UploadFieldTypes;
use Modules\Modules\Support\ForeignFieldTypes;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class ApiExampleResponse
{
	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var string $stubsPath
     */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/CreateForm';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating index example response
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * @param string $foreignFieldSuffix
	 * 
	 * @return string
	 */
	public function index(array $attributes, array $replacements, $foreignFieldSuffix) 
	{	
		return $this->recordDetails($attributes, $replacements, $foreignFieldSuffix) . ',...';
	}

	/**
	 * Handle generating show example response
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * @param string $foreignFieldSuffix
	 * 
	 * @return string
	 */
	public function show(array $attributes, array $replacements, $foreignFieldSuffix) 
	{
		return $this->recordDetails($attributes, $replacements, $foreignFieldSuffix);
	}

	/**
	 * Handle generating errors example response
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * @param string $foreignFieldSuffix
	 * @param bool $firstError
	 * 
	 * @return string
	 */
	public function errors(array $attributes, array $replacements, $foreignFieldSuffix, $firstError) 
	{
		$content = '';

		// int types
		$integerFieldTypes = ['number'];

		// get module fields
		$fields = $attributes['fields'];

		// get the first field and return if true
		if($firstError) {
			return array_key_first($fields);
		}	

		foreach($fields as $key => $fieldAtt) {
			$field = json_decode($fieldAtt, true);

			$fieldName = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_database_column');

            $fieldType = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');
		
            $fieldValidation = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_validation');

            if($fieldType == ForeignFieldTypes::BELONGS_TO) {
            	$fieldName = $fieldName.$foreignFieldSuffix;
            }

            if($fieldValidation == 'required') {
            	$content .= '    
     *          "'.$fieldName.'": [
     *              "The '.$fieldName.' field is required."
     *          ],';
            }
		}

		return $content;
	}

	/**
	 * Handle generating store method example response
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * @param string $foreignFieldSuffix
	 * 
	 * @return string
	 */
	public function store(array $attributes, array $replacements, $foreignFieldSuffix) 
	{
		$content = '';

		// int types
		$integerFieldTypes = ['number'];

		// get module fields
		$fields = $attributes['fields'];

		foreach($fields as $key => $fieldAtt) {
			$field = json_decode($fieldAtt, true);

			$fieldName = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_database_column');

            $fieldType = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            $fieldValidation = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_validation');

            $fieldMultipleFiles = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'multiple_files');

            if($fieldValidation == 'required') {
            	$fieldValidation = 'required';
            }

            $fieldTypeValue = 'string';

            if(in_array($fieldType, $integerFieldTypes)) {
            	$fieldTypeValue = 'integer';
            }

            if($fieldType == ForeignFieldTypes::BELONGS_TO) {
            	$fieldTypeValue = 'integer';
            	$fieldName = $fieldName.$foreignFieldSuffix;
            }

            if($fieldType == ForeignFieldTypes::BELONGS_TO_MANY) {
            	$fieldTypeValue = 'integer[]';
            }

            if(in_array($fieldType, UploadFieldTypes::lists())) {
            	$fieldTypeValue = 'file';

            	if($fieldMultipleFiles == 'on') {
            		$fieldTypeValue = 'file[]';
            	}
            }

			$content .= '
     * @bodyParam '.$fieldName.' ' . $fieldTypeValue . ' ' . $fieldValidation . ' The '.$fieldName.' of the '.$replacements['$SINGULAR_LOWER_NAME_SPACED$'].'.';
		}

		return $content;
	}

	/**
	 * Handle generating record details example response
	 * 
	 * @param array $attributes
	 * @param array $replacements
	 * @param string $foreignFieldSuffix
	 * 
	 * @return string
	 */
	protected function recordDetails(array $attributes, array $replacements, $foreignFieldSuffix) 
	{
		$content = '';
		$excluded = array_merge([ForeignFieldTypes::BELONGS_TO, ForeignFieldTypes::BELONGS_TO_MANY], UploadFieldTypes::lists());

		// get module fields
		$fields = $attributes['fields'];

		foreach($fields as $key => $fieldAtt) {
			$field = json_decode($fieldAtt, true);

			$fieldName = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_database_column');

            $fieldType = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if($fieldType == ForeignFieldTypes::BELONGS_TO) {
                $content .= $this->belongsToForeignFields($field, $foreignFieldSuffix);
            }

            if($fieldType == ForeignFieldTypes::BELONGS_TO_MANY) {
            	$content .= $this->belongsToManyForeignFields($field);
            }

            if(in_array($fieldType, UploadFieldTypes::lists())) {
            	$content .= $this->uploadFields($field);
            }

            if(!in_array($fieldType, $excluded)) {
                $content .= '     *            "' . $fieldName . '": "'.$fieldName.' value",
';
            }
		}

		return $this->indexTemplate($content);
	}

	/**
	 * Handle generating upload type fields
	 * 
	 * @param array $field
	 * 
	 * @return string
	 */
	protected function uploadFields($field) 
	{
		$content = '';

		$fieldName = (new ModuleGeneratorHelper)
            ->getValueByKey($field, 'field_database_column');

		$relatedModel = '\Spatie\MediaLibrary\MediaCollections\Models\Media';
		$defaultFieldValues = $this->deafultUploadValues($fieldName);

		$content .= '     *            "' . $fieldName . '": [
';

		$tableColumns = (new TableColumns($relatedModel))->get();
	
		$content .= '     *            	{
';

		foreach($tableColumns as $column => $value) {
			if(isset($defaultFieldValues[$column])) {
				$content .= '     *                  "' . $column . '": "'.$defaultFieldValues[$column].'",
';
			}
		}

		$content .= '     *            	},
';

		$content .= '     *            ],
';

		return $content;
	}

	/**
	 * Handle generating belongs to foreign fields
	 * 
	 * @param array $field
	 * @param string $foreignFieldSuffix
	 * 
	 * @return string
	 */
	protected function belongsToForeignFields($field, $foreignFieldSuffix) 
	{
		$content = '';

		$fieldName = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_database_column');

		$relatedModel = (new ModuleGeneratorHelper)
            ->getValueByKey($field, 'related_model');

        $record = $relatedModel::first();

		if(!is_null($record)) {
			$content .= '     *            "' . $fieldName . $foreignFieldSuffix . '": '.$record->id.',
';	
			$content .= '     *            "' . $fieldName . '": {
';
			foreach($record->toArray() as $column => $value) {
				if(!is_array($value)) {
					$content .= '     *                  "' . $column . '": "'.$value.'",
';
				}
			}
		} else {
			$tableColumns = (new TableColumns($relatedModel))->get();

			$content .= '     *            "' . $fieldName . $foreignFieldSuffix . '": 1,
';	
			$content .= '     *            "' . $fieldName . '": {
';					
			$content .= '     *                  "id": 1,
';
			foreach($tableColumns as $column => $value) {
				$content .= '     *                  "' . $column . '": "'.$value.' Value",
';
			}
		}

		$content .= '     *            },
';
		return $content;
	}

	/**
	 * Handle generating belongs to many foreign fields
	 * 
	 * @param array $field
	 * 
	 * @return string
	 */
	protected function belongsToManyForeignFields($field) 
	{
		$content = '';

		$fieldName = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_database_column');

		$relatedModel = (new ModuleGeneratorHelper)
            ->getValueByKey($field, 'related_model');

        $records = $relatedModel::limit(2)->get();

		if(count($records) > 0) {
			$content .= '     *            "' . $fieldName . '": [
';

			foreach($records as $record) {
				$content .= '     *            	{
';
				foreach($record->toArray() as $column => $value) {
					if(!is_array($value)) {
						$content .= '     *                  "' . $column . '": "'.$value.'",
';
					}
				}

				$content .= '     *            	},
';
			}
			

			
		} else {
            $content .= '     *            "' . $fieldName . '": [
';
			for ($i=1; $i <= 2; $i++) {
				$tableColumns = (new TableColumns($relatedModel))->get();

				$content .= '     *            	{
';
				foreach($tableColumns as $column => $value) {
					$content .= '     *                  "' . $column . '": "'.$value.' Value",
';
				}

				$content .= '     *            	},
';
			}
		}

		$content .= '     *            ],
';
		return $content;
	}

	/**
	 * Handle generating index method fields template
	 * 
	 * @param string $fields
	 * 
	 * @return string
	 */
	protected function indexTemplate($fields) 
	{
		return '{
     *            "id": 1,
'.$fields.'     *            "created_at": "2022-06-05T05:07:47.000000Z",
     *            "updated_at": "2022-06-05T05:07:47.000000Z"
     *        }';
	}

	/**
	 * Handle generating default field upload type field values
	 * 
	 * @param string $collection
	 * 
	 * @return array
	 */
	protected function deafultUploadValues($collection) 
	{
		return [
			'model_type' => 'Modules\ModuleName\Models\ModelName',
			'model_id' => 1,
			'uuid' => '7b92dd82-7148-43b9-b54b-2561b55f1037',
			'collection_name' => $collection,
			'name' => 'file_name',
			'file_name' => 'file_name.jpg',
			'mime_type' => 'image/jpeg',
			'disk' => 'public',
			'conversions_disk' => 'public',
			'size' => 1000,
			'manipulations' => '[]',
			'custom_properties' => '[]',
			'generated_conversions' => '[]',
			'responsive_images' => '[]',
			'order_column' => 1,
			'created_at' => '2022-06-25T09:16:21.000000Z',
			'updated_at' => '2022-06-25T09:16:21.000000Z',
			'url' => 'https://domain.com/storage/1/file_name.jpg',
            'thumbnail' => 'https://domain.com/storage/1/conversions/file_name-thumb.jpg',
            "preview"=> 'https://domain.com/storage/1/conversions/file_name-preview.jpg',
            "original_url"=> 'https://domain.com/storage/1/file_name.jpg',
            'preview_url'=> ''
		];
	}
}