<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class ShowRecordGenerator
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
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/ShowRecord';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating show record view
	 * 
	 * @param string $stub
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generate($stub, array $moduleReplacements) 
	{
		$path = base_path() . $this->stubsPath . '/' . $stub;

		$content = $this->filesystem->get($path);

		return (new ModuleGeneratorHelper)->replace($moduleReplacements, $content);
		
	}
}