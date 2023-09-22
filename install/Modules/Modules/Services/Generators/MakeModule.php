<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Modules\Modules\Support\TableDelete;
use Modules\Modules\Support\ModuleResetMedias;
use Modules\Modules\Repositories\ModuleRepository;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MakeModule
{	
	/**
	 * The module path
	 * 
	 * @var string $path
	 */
	protected $path;

	/**
	 * The module replacements
	 * 
	 * @var array $replacements
	 */
	protected $replacements = [];

	/**
	 * Module Repository
	 * 
	 * @var ModuleRepository $modules
	 */
	protected $modules;

	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @param string $path
     * @param array $replacements
     */
	public function __construct($path, $replacements = []) 
	{
		$this->path = $path;
		$this->replacements = $replacements;
		$this->filesystem = new Filesystem;
		$this->modules = new ModuleRepository;
	}

	/**
	 * Make module directory
	 * 
	 * @param string $path The path for module
	 * 
	 * @return void
	 */
	public function make() 
	{
		if(!$this->filesystem->exists($this->path)) {
            $this->filesystem->makeDirectory($this->path);
        } else {
        	$this->delete();
        	// Make re-make module directory
        	$this->filesystem->makeDirectory($this->path);
        }
	}

	/**
	 * Delete module
	 * 
	 * @return @void
	 */
	public function delete() 
	{
		//delete the module for rebuilding the code
    	// $this->filesystem->deleteDirectory($this->path);
    	Artisan::call('module:delete', [
    		'module' => $this->replacements['$MODULE$']
    	]);
    	
    	// Delete table
    	$this->deleteTable();
    	// Delete module medias on re-make
    	$this->deleteMedias();
	}

	/**
	 * Delete database table of the selected module to update
	 * 
	 * @return void
	 */
	protected function deleteTable() 
	{
		$module = $this->modules->findBy('name', $this->replacements['$MODULE$']);

		if(!is_null($module)) {
			$attributes = $module->attributes;

			if(isset($attributes['table_names'])) {
				foreach($attributes['table_names'] as $tableName) {
					$tableDelete = new TableDelete($tableName);
					$tableDelete->execute();
				}
			}
		}
		
	}

	/**
	 * Delete module medias for update
	 * 
	 * @return void
	 */
	protected function deleteMedias() 
	{
		$namespace = 'Modules\\'.$this->replacements['$MODULE$'].'\Models\\'.$this->replacements['$MODEL$'];

		$medias = Media::where('model_type', $namespace)->get();

		foreach($medias as $media) {
			$media->delete();
		}
	}
}