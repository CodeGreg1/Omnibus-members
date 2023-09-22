<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class HiddenGenerator
{
	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * Handle generating hidden fields for model
     * 
     * @param array $fields
     * @param array $moduleReplacements
     * 
     * @return string
     */
	public function generate(array $fields, array $moduleReplacements) 
	{
		$fieldsContent = '';
		$content = '

	/**
     * @var array $hidden
     */
    protected $hidden = [
        ';

		foreach ($fields as $key=>$fieldAtt) {
			$field = json_decode($fieldAtt, true);

			$type = (new ModuleGeneratorHelper)->getValueByKey($field, 'field_type');
			$column = (new ModuleGeneratorHelper)->getValueByKey($field, 'field_database_column');

			if ($type == 'password') {
				$fieldsContent .= "
        '".$column.'\',';
			}
		}

		// check if last has , character
        if(substr($fieldsContent, -1) == ',') {
            $content .= trim($fieldsContent);
        }

		$content .= "
    ];";

    	if($fieldsContent != '') {
    		return $content;
    	}
		
		return $fieldsContent;
		
	}
}