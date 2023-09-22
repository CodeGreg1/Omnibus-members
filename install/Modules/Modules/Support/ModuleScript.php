<?php

namespace Modules\Modules\Support;

use Illuminate\Filesystem\Filesystem;

class ModuleScript {

	/**
	 * @var Filesystem filesystem
	 */
	protected $filesystem;

	/**
	 * @var string $jsDir
	 */
	protected $jsDir = '/js/tmp/modules';

	public function __construct() 
	{
		$this->filesystem = new Filesystem;
	}

	/**
	 * Handle on compiling module javascript and save to the public
	 * 
	 * NOTE: The generated js file in corresponding folder above will 
	 * be remove when running npm this is temporary only after 
	 * the module is generated
	 * 
	 * @param string $path
	 * @param array $replacements
	 * 
	 * @return void
	 */
	public function compile($path, $replacements) 
	{	
		$scripts = $this->filesystem->files($path . '/Resources/assets/js');

        $scriptContent = '';
        foreach($scripts as $script) {
            $scriptContent .= '(function () {';
            $scriptContent .= file_get_contents($script->getPathname());
            $scriptContent .= '})();';
        }

        if(!$this->filesystem->exists(public_path() . $this->jsDir)) {
            $this->filesystem->makeDirectory(public_path() . $this->jsDir);
        }

        $this->filesystem->put(public_path() . $this->jsDir . '/' . $replacements['$SNAKE_NAME$'] . '.js', $scriptContent);
	}

}