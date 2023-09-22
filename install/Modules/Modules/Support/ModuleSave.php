<?php

namespace Modules\Modules\Support;

use Illuminate\Support\Str;
use Modules\Modules\Repositories\ModuleRepository;
use Modules\Modules\Repositories\ModuleRelationRepository;

class ModuleSave {

    /**
     * @var array $attributes
     */
	protected $attributes = [];

    /**
     * @var array $replacements
     */
    protected $replacements = [];

    /**
     * @var array $tableRelations
     */
    protected $tableRelations = [];

    /**
     * @param array $attributes
     * @param array $replacements
     * @param array $tableRelations
     */
    public function __construct($attributes, $replacements, $tableRelations) 
    {
    	$this->attributes = $attributes;
        $this->replacements = $replacements;
        $this->tableRelations = $tableRelations;
    }
    
    /**
     * Handle creating/updating module details
     * 
     * @return \Modules\Modules\Models\Module
     */
    public function execute() 
    {
    	$name = $this->replacements['$MODULE$'];
        $handle = $this->replacements['$PLURAL_KEBAB_NAME$'];
        $tableName = $this->replacements['$TABLE_MODEL$'];
        
        $attributes = json_encode($this->attributes);

        $module = new ModuleRepository;

        $moduleDetails = $module->updateOrCreate([
            'name' => $name
        ], [
            'name' => $name,
            'handle' => $handle,
            'table_name' => $tableName,
            'attributes' => $attributes,
            'is_ran_migration' => 0,
            'pro_access' => $this->isProAccess()
        ]);

        // get the previous related modules
        $prevRelatedModuleIds = (new ModuleRelationRepository)->findBy('module_id', $moduleDetails->id);

        if(!is_null($prevRelatedModuleIds)) {
            $prevRelatedModuleIds = $prevRelatedModuleIds->pluck('module_related_id')->toArray();
        } else {
            $prevRelatedModuleIds = [];
        }
        
        foreach($this->tableRelations as $table) {
            $relatedModule = (new ModuleRepository)->findBy('table_name', $table);

            // check if table has a module recorded
            if($relatedModule) {
                
                (new ModuleRelationRepository)->updateOrCreate([
                    'module_id' => $moduleDetails->id,
                    'module_related_id' => $relatedModule->id
                ],[
                    'module_id' => $moduleDetails->id,
                    'module_related_id' => $relatedModule->id
                ]);

                if(is_array($prevRelatedModuleIds)) {
                    // get key of the current module id value
                    $key = array_search($relatedModule->id, $prevRelatedModuleIds);
                    // remove the current module id
                    unset($prevRelatedModuleIds[$key]);
                }
                
            }
        }

        if(count($prevRelatedModuleIds)) {
            // delete removed related module
            (new ModuleRelationRepository)
                ->where('module_id', $moduleDetails->id)
                ->whereIn('module_related_id', $prevRelatedModuleIds)
                ->delete();
        }

        return $moduleDetails;
    }

    /**
     * Handle checking if the module is only accessible for subscribed users
     * 
     * @return bool
     */
    protected function isProAccess() 
    {
        return isset($this->attributes['included']['only_subscribers']) && $this->attributes['included']['only_subscribers'] == 'on';
    }

}