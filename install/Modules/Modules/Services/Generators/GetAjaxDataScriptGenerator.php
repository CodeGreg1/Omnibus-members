<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class GetAjaxDataScriptGenerator
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
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/GetAjaxData';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating ajax data script
	 * 
	 * @param string $template
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generate($template, array $moduleReplacements) 
	{
		$path = base_path() . $this->stubsPath . '/' . $template . '.stub';

		$content = $this->filesystem->get($path);

		$content = (new ModuleGeneratorHelper)->replace($moduleReplacements, $content);

		return $content;
		
	}
}