<?php

namespace Modules\Modules\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Support\Language\Translations;
use Modules\Modules\Repositories\ModuleRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class ModulesEditLanguageDatatableController extends BaseController
{   
    /**
     * @var ModuleRepository $modules
     */
    protected $modules;

    /**
     * @var ModuleRepository $modules
     */
    public function __construct(ModuleRepository $modules) 
    {
        $this->modules = $modules;

        parent::__construct();
    }

    /**
     * Handle module language phrases datatable
     * @return JsonResponse
     */
    public function index($id, $code)
    {   
        $this->authorize('admin.modules.datatable');
        
        $translations = new Translations;

        $module = $this->modules->find($id);

        $name = $module->name; //strtolower($module->name);

        // check if language is not yet existing for this module then copy default
        if(!$translations->moduleHasLanguage($name, $code)) {
            $translations->copyModuleEnglishLanguageByCode($name, $code);
        }

        $content = $translations->getByModule($name, $code);

        return $translations->datatable($content);
    }
}
