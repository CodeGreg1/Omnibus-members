<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class Select2PluginGenerator
{
	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var array $template
     */
	protected $template = [
		'styles' => 'styles.stub',
		'scripts' => 'scripts.stub'
	];

	/**
	 * @var string $stubsPath
	 */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Select2';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle genereating select2 plugin
	 * 
	 * @param string $type
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generate($type, array $moduleReplacements) 
	{
		$path = base_path() . $this->stubsPath . '/' . $this->template[$type];

		$content = $this->filesystem->get($path);

		$content = (new ModuleGeneratorHelper)->replace($moduleReplacements, $content);

		return $content;
		
	}
}