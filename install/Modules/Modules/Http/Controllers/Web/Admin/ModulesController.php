<?php

namespace Modules\Modules\Http\Controllers\Web\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Spatie\Permission\Models\Role;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Modules\Modules\Support\AllModels;
use Illuminate\Support\Facades\Artisan;
use Modules\Modules\Support\FieldTypes;
use Modules\Modules\Support\TableColumns;
use Modules\Modules\Events\ModulesCreated;
use Modules\Modules\Events\ModulesRemoved;
use Modules\Modules\Events\ModulesUpdated;
use Illuminate\Contracts\Support\Renderable;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Modules\Support\ForeignFieldTypes;
use Modules\Base\Support\Language\Translations;
use Modules\Languages\Services\GoogleTranslate;
use Modules\Modules\Repositories\ModuleRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Modules\Http\Requests\StoreModulesRequest;
use Modules\Languages\Repositories\LanguagesRepository;
use Modules\Modules\Events\ModulesLanguagePhraseCreated;
use Modules\Modules\Events\ModulesLanguagePhraseUpdated;
use Modules\Modules\Services\Generators\ModuleGenerator;
use Modules\Languages\Http\Requests\AddLanguagePhraseRequest;
use Modules\Modules\Http\Requests\UpdateModuleLanguageRequest;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;
use Modules\Modules\Http\Requests\TranslateModuleLanguageRequest;

class ModulesController extends BaseController
{
    /**
     * @var MenusRepository $menus
     */
    protected $menus;

    /**
     * @var ModuleRepository $modules
     */
    protected $modules;

    /**
     * @var string $redirectedTo
     */
    protected $redirectedTo = '/admin/module';

    /**
     * @var LanguagesRepository $languages
     */
    protected $languages;

    /**
     * @var MenuRepository $menus
     * @var ModuleRepository $modules
     * @var LanguagesRepository $languages
     */
    public function __construct(
        MenuRepository $menus,
        ModuleRepository $modules,
        LanguagesRepository $languages) 
    {
        $this->modules = $modules;
        $this->languages = $languages;
        $this->menus = $menus;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {   
        $this->authorize('admin.modules.index');

        return view('modules::admin.index', [
            'pageTitle' => __(config('modules.name')),
            'isNpmRan' => $this->modules
                ->where('is_ran_npm', 0)
                ->count(),
            'policies' => JsPolicy::get('modules', '.', [
                'show-language'
            ])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.modules.create');

        $menus = $this->menus->getMenuByName('Admin');

        return view('modules::admin.create', [
            'roles' => Role::all(),
            'models' => json_encode((new AllModels)->get()),
            'pageTitle' => __('Create new module'),
            'menuLinks' => $menus->links->whereNull('parent_id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreModulesRequest $request
     * @return JsonResponse
     */
    public function store(StoreModulesRequest $request)
    {
        if($this->isFieldTypeExists() !== null) {
            return $this->errorResponse($this->isFieldTypeExists());
        }

        if($this->isForeignFieldTypeModelExists() !== null) {
            return $this->errorResponse($this->isForeignFieldTypeModelExists());
        }

        if( !$this->isWritableModulesStatusesJson() ) {
            return $this->errorResponse(__('The modules_statuses.json is not writable. Please change file permission to 777 so that we can able to generate module status. You can find this file inside project root directory.'));
        }

        $attributes = $request->all();

        // convert uppercase the first letter of the words
        $attributes['module_name'] = ucwords($attributes['module_name']);
        $attributes['menu_title']['name'] = ucwords($attributes['menu_title']['name']);
        
        if($request->has('id')) {
            $module = $this->modules->find($request->get('id'));

            if(isset($module->attributes['snake_date'])) {
                $attributes['snake_date'] = $module->attributes['snake_date'];
            }

            event(new ModulesUpdated($module));

            if($module->is_core) {
                abort(403, __('Not allowed to rebuild this module.'));
            }
        }

        $module = (new ModuleGenerator($attributes))->generate();

        if(!$request->has('id')) {
            event(new ModulesCreated($module));
        }

        return $this->handleAjaxRedirectResponse(
            __('Module code generated successfully.'), 
            $this->redirectedTo
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.modules.edit');

        $module = $this->modules->find($id);

        if($module->is_core) {
            abort(403, __('Not allowed to rebuild this module.'));
        }

        $menus = $this->menus->getMenuByName('Admin');

        return view('modules::admin.edit', [
            'module' => $module['attributes'],
            'roles' => Role::all(),
            'models' => json_encode((new AllModels)->get()),
            'pageTitle' => __('Edit module'),
            'menuLinks' => $menus->links->whereNull('parent_id')
        ]);
    }

    /**
     * Show the form for editing language the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function showLanguages($id) 
    {
        $this->authorize('admin.modules.show-language');

        $module = $this->modules->find($id);
        $languages = $this->languages->getExcludeEnglish();

        return view('modules::admin.show-languages', [
            'pageTitle' => __('Show module languages'),
            'module' => $module,
            'languages' => $languages,
            'addLanguagePhraseRoute' => route('admin.modules.add-language-phrase', $module->id),
            'autoTranslateAddingPhrase' => route('admin.languages.auto-translate-adding-phrase'),
            'policies' => JsPolicy::get('modules', '.', [
                'show-language',
                'edit-language'
            ])
        ]);
    }

    /**
     * Show the form for editing the language resource
     * @param int $id
     * @param string $code
     * @return Renderable
     */
    public function editLanguage($id, $code) 
    {
        $this->authorize('admin.modules.edit-language');

        $module = $this->modules->find($id);

        return view('modules::admin.edit-language', [
            'pageTitle' => __('Edit module language'),
            'code' => $code,
            'module' => $module
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.modules.delete');

        $module = $this->modules->with(['relations'])->findOrFail($request->get('id'));

        if($module->is_core == 1 || $module->relations->count() > 0) {
            return $this->errorResponse(__("Module can't be deleted."));
        }

        (new ModuleGenerator($module->attributes))->delete();

        if($module->delete()) {
            event(new ModulesRemoved($module));
        }

        return $this->handleAjaxRedirectResponse(
            __('Module removed successfully.'), 
            $this->redirectedTo
        );
    }

    /**
     * Getting all models
     * 
     * @return JsonResponse
     */
    public function getModels() 
    {
        $this->authorize('admin.modules.get-models');

        return (new AllModels)->get();
    }

    /**
     * Get the table columns
     * 
     * @param Request $request
     * 
     * @return JsonResponse|null
     */
    public function tableColumns(Request $request) 
    {
        $this->authorize('admin.modules.table-columns');

        if($request->get('table')) {
            return (new TableColumns($request->get('table')))->get();
        }
        return null;
    }

    /**
     * Get the module name
     * 
     * @param string $name
     * 
     * @return JsonResponse
     */
    public function moduleName($name) 
    {
        $this->authorize('admin.modules.name');

        $generator = (new ModuleGenerator(['module_name' => $name]));
        return [
            'module' => $name,
            'lower_name' => $generator->getLowerModule(),
            'model_name' => $generator->getModelName()
        ];
    }

    /**
     * Handle updating module language
     * 
     * @param UpdateModuleLanguageRequest $request
     * 
     * @return JsonResponse
     */
    public function updateModuleLanguage(UpdateModuleLanguageRequest $request) 
    {   
        $translations = new Translations;

        $modules = $this->modules->findOrFail($request->get('id'));

        $translations->putByModule(
            $modules->name, 
            $request->get('code'), 
            $request->get('key'), 
            $request->get('value')
        );

        event(new ModulesLanguagePhraseUpdated(
            $modules, 
            $request->get('code'),
            $request->get('key')
        ));

        return $this->successResponse(__('Language phrase updated successfully.'));
    }

    /**
     * Handle to execute google translate for module language
     * 
     * @param TranslateModuleLanguageRequest $request
     * 
     * @return JsonResponse
     */
    public function translateModuleLanguage(TranslateModuleLanguageRequest $request) 
    {   
        $translations = new Translations;

        $modules = $this->modules->findOrFail($request->get('id'));

        $translated = GoogleTranslate::trans($request->get('value'), $request->get('code'));

        $translations->putByModule(
            $modules->name, 
            $request->get('code'), 
            $request->get('key'), 
            $translated
        );

        return $this->successResponse(__('Language phrase translated successfully.'), [
            'value' => $translated
        ]);
    }

    /**
     * Handle adding new language phrase
     * 
     * @param AddLanguagePhraseRequest $request
     * 
     * @param JsonResponse
     */
    public function addLanguagePhrase(AddLanguagePhraseRequest $request) 
    {
        $translations = new Translations;
        $modules = $this->modules->findOrFail($request->route('id'));

        foreach($request->get('value') as $code => $phrase) {
            if(!$translations->moduleHasLanguage($modules->name, $code)) {
                $translations->copyModuleEnglishLanguageByCode(
                    $modules->name, 
                    $code
                );
            }

            $translations->putByModule(
                $modules->name,
                $code,
                $request->get('key'),
                $phrase
            );
        }

        event(new ModulesLanguagePhraseCreated(
            $modules, 
            $request->get('key')
        ));

        return $this->successResponse(__('Language phrase added successfully.'));
    }

    /**
     * Handle on checking migration if already ran
     * 
     * @return Module
     */
    public function migrationCheck() 
    {
        return $this->modules->where('is_ran_migration', 0)->count();
    }

    /**
     * Handle on running migration then update status on modules record
     * 
     * @return void
     */
    public function migrationRun() 
    {
        $count = $this->modules->where('is_ran_migration', 0)->count();

        if($count) {
            Artisan::call('migrate', [
                '--force' => true,
            ]);
            $this->modules->setMigrationRanAll();
        }
    }

    /**
     * Check if modules_statuses.json is writable
     * 
     * @return boolean
     */
    protected function isWritableModulesStatusesJson() 
    {
        return is_writable(base_path() . '/modules_statuses.json');
    }

    /**
     * Check if foreign field type model class is exists
     * 
     * @return string|null
     */
    protected function isForeignFieldTypeModelExists() 
    {
        foreach(request('fields') as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(in_array($type, ForeignFieldTypes::lists())) {
                $value = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'related_model');

                if(!class_exists($value)) {
                    return __('<b>:class</b> model class does not exist. Please double check your selected foreign model.', ['class' => $value]);
                }
            }
        }
    }

    /**
     * Check if field type is exists in the generator
     * 
     * @return string|null
     */
    protected function isFieldTypeExists() 
    {
        foreach(request('fields') as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(!in_array($type, FieldTypes::lists())) {
                return __('Field type <b>:type</b> does not exist.', ['type' => $type]);
            }
        }
    }
}
