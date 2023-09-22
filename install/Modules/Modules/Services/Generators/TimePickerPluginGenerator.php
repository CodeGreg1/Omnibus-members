<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class TimePickerPluginGenerator
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
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/TimePicker';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating time picker plugin script
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