<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class SoftDeleteInitScriptsGenerator
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
	protected $template = 'softDeleteInitScripts.stub';

	/**
	 * @var string $stubsPath
	 */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Datatable';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating script for soft delete init
	 * 
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generate(array $moduleReplacements) 
	{
		$path = base_path() . $this->stubsPath . '/' . $this->template;

		$content = $this->filesystem->get($path);

		$content = (new ModuleGeneratorHelper)->replace($moduleReplacements, $content);

		return $content;
		
	}
}