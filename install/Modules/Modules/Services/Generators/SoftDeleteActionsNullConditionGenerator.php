<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class SoftDeleteActionsNullConditionGenerator
{
	/**
	 * Handle generating open for deleted at code condition
	 * 
	 * @return string
	 */
	public function generateOpen() 
	{
		return '
                        if(row.deleted_at === null) ';
	}

	/**
	 * Handle generating close for deleted at code condition
	 * 
	 * @return string
	 */
	public function generateClose() 
	{
		return '
                        }';
	}
}