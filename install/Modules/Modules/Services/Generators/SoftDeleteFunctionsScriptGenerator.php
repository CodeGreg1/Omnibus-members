<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class SoftDeleteFunctionsScriptGenerator
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
	protected $template = 'softDeleteFunctionScripts.stub';

	/**
	 * @var string $stubsPath
	 */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Datatable';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating controller method for soft delete
	 * 
	 * @param array $moduleReplacements
	 * 
	 * @return array
	 */
	public function generate(array $moduleReplacements) 
	{
		$path = base_path() . $this->stubsPath . '/' . $this->template;

		$content = $this->filesystem->get($path);

		$content = (new ModuleGeneratorHelper)->replace($moduleReplacements, $content);

		return $content;
		
	}
}