<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class SoftDeleteRoutesGenerator
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
	protected $template = 'softDeletesRoutes.stub';

	/**
	 * @var string $stubsPath
	 */
	protected $stubsPath = '/Modules/Modules/Services/Generators/Stubs/Routes';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle generating soft delete route
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