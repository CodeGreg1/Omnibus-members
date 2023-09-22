<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Modules\Modules\Support\ModuleScript;
use Modules\Modules\Support\ModuleSave;
use Modules\Menus\Support\MenuLinkSaver;
use Modules\Menus\Support\MenuLinkDeleter;
use Modules\Modules\Support\TableColumns;
use Modules\Modules\Support\ModelByNamespace;
use Modules\Modules\Support\UploadFieldTypes;
use Modules\Modules\Support\ForeignFieldTypes;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Modules\Support\CrudTypeMenuSaver;
use Modules\Modules\Support\CrudTypeReplacements;
use Modules\Modules\Support\ModuleSavePermissions;
use Modules\Modules\Services\Generators\MakeModule;
use Modules\Modules\Support\GetCreatingModuleRoutes;
use Modules\Modules\Support\ModuleRemovePermissions;
use Modules\Modules\Support\ModuleAssignPermissions;
use Modules\Modules\Services\Generators\Form\TextForm;
use Modules\Modules\Services\Generators\HiddenGenerator;
use Modules\Modules\Services\Generators\UploadGenerator;
use Modules\Modules\Services\Generators\RequestGenerator;
use Modules\Modules\Services\Generators\FillableGenerator;
use Modules\Modules\Services\Generators\Form\TextareaForm;
use Modules\Modules\Services\Generators\EditFormGenerator;
use Modules\Modules\Services\Generators\DatatableGenerator;
use Modules\Modules\Services\Generators\OrderableGenerator;
use Modules\Modules\Services\Generators\ViewFieldGenerator;
use Modules\Modules\Services\Generators\MigrationsGenerator;
use Modules\Modules\Services\Generators\SearchableGenerator;
use Modules\Modules\Services\Generators\CreateFormGenerator;
use Modules\Modules\Services\Generators\ShowRecordGenerator;
use Modules\Modules\Services\Generators\MakeModuleStructures;
use Modules\Modules\Services\Generators\ChoicesModelGenerator;
use Modules\Modules\Services\Generators\ModelAppendsGenerator;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;
use Modules\Modules\Services\Generators\ModelMutatorsGenerator;
use Modules\Modules\Services\Generators\RouteProviderGenerator;
use Modules\Modules\Services\Generators\Select2PluginGenerator;
use Modules\Modules\Services\Generators\SoftDeleteRoutesGenerator;
use Modules\Modules\Services\Generators\SoftDeleteActionsGenerator;
use Modules\Modules\Services\Generators\GetAjaxDataScriptGenerator;
use Modules\Modules\Services\Generators\TinyMCEInitScriptGenerator;
use Modules\Modules\Services\Generators\TinyMCEConfigScriptGenerator;
use Modules\Modules\Services\Generators\UpdateOrCreateFieldsGenerator;
use Modules\Modules\Services\Generators\SoftDeleteInitScriptsGenerator;
use Modules\Modules\Services\Generators\BelongsToManyMigrationGenerator;
use Modules\Modules\Services\Generators\SoftDeleteConfigRoutesGenerator;
use Modules\Modules\Services\Generators\SoftDeleteFunctionsScriptGenerator;
use Modules\Modules\Services\Generators\SoftDeleteControllerFunctionsGenerator;

class ModuleGenerator 
{
	/**
     * The module name will created.
     *
     * @var string
     */
    protected $name;

    /**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var string $modulePath
     */
    protected $modulePath;

    /**
     * @var array $moduleAttributes
     */
    protected $moduleAttributes;

    /**
     * @var string $foreignFieldSuffix
     */
    protected $foreignFieldSuffix = '_id';

    /**
     * @var array $defaultRoutes
     */
    protected $defaultRoutes = [
        '.index',
        '.create',
        '.store',
        '.show',
        '.edit',
        '.update',
        '.delete',
        '.multi-delete',
        '.restore',
        '.force-delete',
        '.datatable',
        '.remove-media'
    ];

    /**
     * The constructor.
     * 
     * @param array $attributes
     * @param Filesystem $filesys
     */
    public function __construct($attributes = array()) 
    {
    	$this->name = Str::plural($attributes['module_name']);
        $this->moduleAttributes = $attributes;
    	$this->filesystem = new Filesystem;

    	$this->setModulePath();
    }

    /**
     * Handle generating module
     * 
     * @return \Modules\Modules\Models\Module
     */
    public function generate() 
    {
        set_time_limit(500);

    	(new MakeModule(
            $this->modulePath, 
            $this->replacements()
        ))->make();

        (new MakeModuleStructures(
            $this->modulePath, 
            $this->replacements(), 
            $this->moduleAttributes
        ))->make();

        Artisan::call('module:enable', ['module' => $this->getStudlyModule()]);

        (new ModuleSavePermissions(array_merge([
            'module' => $this->replacements()['$PLURAL_KEBAB_NAME$'],
            'routes' => $this->defaultRoutes,
            'roles' => $this->moduleAttributes['roles'],
         ], $this->moduleAttributes)))->execute();

        (new CrudTypeMenuSaver(
            $this->moduleAttributes, 
            $this->replacements(),
            'Admin',
            'admin'
        ))->execute();

        (new CrudTypeMenuSaver(
            $this->moduleAttributes, 
            $this->replacements(),
            'User',
            'user'
        ))->execute();

        $this->storeTableNames([$this->replacements()['$TABLE_MODEL$']]);

        $this->appendSnakeDate();

        $module = (new ModuleSave(
            $this->moduleAttributes, 
            $this->replacements(), 
            $this->getTableRelations()
        ))->execute();

        $this->deletePreviousMigrations();

        (new ModuleScript)->compile($this->modulePath, $this->replacements());

        Artisan::call('cache:clear');

        return $module;
    }

    /**
     * Handle deleting module
     * 
     * @return void
     */
    public function delete() 
    {
        set_time_limit(500);

        (new MakeModule(
            $this->modulePath, 
            $this->replacements()
        ))->delete();

        $modulePermissions = (new GetCreatingModuleRoutes(array_merge([
            'module' => $this->replacements()['$PLURAL_KEBAB_NAME$'],
            'roles' => $this->moduleAttributes['roles'],
            'routes' => $this->defaultRoutes
        ], $this->moduleAttributes)))->lists();

        foreach($modulePermissions as $modulePermission) {
            (new MenuLinkDeleter([
                'link' => $modulePermission,
            ]))->execute();
        }

        (new ModuleRemovePermissions(array_merge([
            'module' => $this->replacements()['$PLURAL_KEBAB_NAME$'],
            'roles' => $this->moduleAttributes['roles'],
            'routes' => $this->defaultRoutes
        ], $this->moduleAttributes)))->execute();
    }

    /**
     * Handle generating replacements with shortcode and value
     * 
     * @return array
     */
    public function replacements() 
    {
        $replacements = [
            '$MODULE$' => $this->getStudlyModule(),
            '$LOWER_NAME$' => $this->getLowerModule(),
            '$LOWER_NAME_SPACED$' => $this->getLowerSpacedModule(),
            '$SINGULAR_LOWER_NAME_SPACED$' => $this->getSingularLowerSpacedModule(),
            '$START_UPPER_NAME_SPACED$' => $this->getStartUpperSpacedModule(),
            '$SINGULAR_START_UPPER_NAME_SPACED$' => $this->getSingularStartUpperSpacedModule(),
            '$PLURAL_START_UPPER_NAME_SPACED$' => $this->getPluralStartUpperSpacedModule(),
            '$PLURAL_LOWER_NAME_SPACED$' => $this->getPluralLowerSpacedModule(),
            '$STRTOUPPER_NAME$' => $this->getStartUpperModule(),
            '$STUDLY_NAME$' => $this->getStudlyModule(),
            '$CAMEL_NAME$' => $this->getCamelModule(),
            '$SNAKE_NAME$' => $this->getSnakeModule(),//UserStatus = user_status
            '$KEBAB_NAME$' => $this->getKebabModule(),//UserStatus = user-status
            '$PLURAL_KEBAB_NAME$' => $this->getPluralKebabModule(),//UserStatus = user-statuses
            '$SINGULAR_NAME$' => $this->getSingularModule(),
            '$SINGULAR_LOWER_NAME$' => $this->getSingularLowerModule(),
            '$PLURAL_LOWER_NAME$' => $this->getPluralLowerModule(),
            '$MODEL$' => $this->getModelName(),
            '$MODEL_PLURAL$' => $this->getPluralModelName(),
            '$SNAKE_MODEL$' => $this->getSnakeModelName(),
            '$KEBAB_MODEL$' => $this->getKebabModelName(),
            '$TABLE_MODEL$' => $this->getTableModelName(),
            '$SNAKE_DATE$' => $this->getSnakeDateType()
        ];

        $otherReplacements = [
            '$LANGUANGE_FORM_FIELDS$' => $this->languageFormFieldsGenerator(),
            '$CREATE_FORM_FIELDS$' => $this->formGenerator('create', $replacements),
            '$EDIT_FORM_FIELDS$' => $this->formGenerator('edit', $replacements),
            '$DATATABLE_COLUMNS$' => $this->datatableGenerator($replacements),
            '$MIGRATIONS_COLUMNS$' => $this->migrationsGenerator($replacements),
            '$FOREIGN_MIGRATIONS_COLUMNS$' => $this->foreignMigrationsGenerator($replacements),
            '$MIGRATIONS_SOFT_DELETES_COLUMN$' => $this->migrationsSoftDeleteGenerator(),
            '$MODEL_SOFT_DELETES_USE$' => $this->softDeletesModelUseGenerator(),
            '$MODEL_SOFT_DELETES_IMPORT$' => $this->softDeletesModelImportGenerator(),
            '$DATATABLE_SOFT_DELETES_FILTER_TABS$' => $this->softDeletesFilterTabsGenerator(),
            '$DATATABLE_SOFT_DELETES_FILTER_CONTROL$' => $this->softDeletesFilterControlGenerator(),
            '$DATATABLE_SOFT_DELETES_FUNCTIONS_SCRIPT$' => $this->softDeletesFunctionsScriptGenerator($replacements),
            '$DATATABLE_SOFT_DELETES_CONFIG_ROUTES$' => $this->softDeletesConfigRoutesGenerator($replacements),
            '$DATATABLE_SOFT_DELETES_ACTIONS$' => $this->softDeletesActionsGenerator($replacements),
            '$DATATABLE_SOFT_DELETES_INIT_SCRIPTS$' => $this->softDeletesInitScriptsGenerator($replacements),
            '$SOFT_DELETES_CONTROLLER_FUNCTIONS$' => $this->softDeleteControllerFunctionsGenerator($replacements),
            '$SOFT_DELETES_ROUTES$' => $this->softDeleteRoutesGenerator($replacements),
            '$FILLABLE_COLUMNS$' => $this->fillableGenerator($replacements),
            '$HIDDEN_COLUMNS$' => $this->hiddenGenerator($replacements),
            '$SEARCHABLE_COLUMNS$' => $this->searchableGenerator($replacements),
            '$ORDERABLE_COLUMNS$' => $this->orderableGenerator($replacements),
            '$STORE_COLUMNS$' => $this->updateOrCreateGenerator('create', $replacements),
            '$UPDATE_COLUMNS$' => $this->updateOrCreateGenerator('edit', $replacements),
            '$CREATE_VALIDATION_COLUMNS$' => $this->validationGenerator('create', $replacements),
            '$EDIT_VALIDATION_COLUMNS$' => $this->validationGenerator('edit', $replacements),
            '$TINYMCE_CONFIG_SCRIPT$' => $this->tinyMCEConfigScript($replacements),
            '$TINYMCE_INIT_SCRIPT$' => $this->tinyMCEInitScript($replacements),
            '$TINYMCE_PLUGIN_SCRIPT$' => $this->tinyMCEPluginScript($replacements),
            '$TINYMCE_FORM_DATA_SCRIPT$' => $this->tinyMCEFormDataScript($replacements),
            '$CREATE_AJAX_DATA_SCRIPT$' => $this->getAjaxDataScript('Create', $replacements),
            '$UPDATE_AJAX_DATA_SCRIPT$' => $this->getAjaxDataScript('Update', $replacements),
            '$CREATE_INDEX_BUTTON$' => $this->createFormGenerator('indexCreateButton.stub', $replacements),
            '$CREATE_ROUTE$' => $this->createFormGenerator('createRoute.stub', $replacements),
            '$CREATE_CONTROLLER$' => $this->createFormGenerator('createController.stub', $replacements),
            '$API_CREATE_CONTROLLER$' => $this->createFormGenerator('apiCreateController.stub', $replacements),
            '$EDIT_CONTROLLER$' => $this->editFormGenerator('editController.stub', $replacements),
            '$EDIT_ROUTE$' => $this->editFormGenerator('editRoute.stub', $replacements),
            '$EDIT_DATATABLE_BUTTON$' => $this->editFormGenerator('datatableEditButton.stub', $replacements),
            '$SHOW_FIELD$' => $this->viewFieldGenerator($replacements),
            '$SHOW_DATATABLE_BUTTON$' => $this->showRecordGenerator('datatableShowButton.stub', $replacements),
            '$SHOW_CONTROLLER$' => $this->showRecordGenerator('showFunction.stub', $replacements),
            '$SHOW_ROUTE$' => $this->showRecordGenerator('showRoute.stub', $replacements),
            '$DELETE_DATATABLE_BUTTON$' => $this->deleteRecordGenerator('datatableDeleteButton.stub', $replacements),
            '$DELETE_CONTROLLER$' => $this->deleteRecordGenerator('deleteController.stub', $replacements),
            '$DELETE_ROUTE$' => $this->deleteRecordGenerator('deleteRoute.stub', $replacements),
            '$DELETE_MULTI_CONTROLLER$' => $this->deleteMultiRecordGenerator('deleteMultiRecord.stub', $replacements),
            '$DELETE_MULTI_ROUTE$' => $this->deleteMultiRecordGenerator('deleteMultiRoute.stub', $replacements),
            '$DATATABLE_DELETE_MULTI_FUNCTIONS_SCRIPT$' => $this->deleteMultiRecordGenerator('deleteMultiDatatableFunctionScript.stub', $replacements),
            '$DATATABLE_DELETE_MULTI_BULK_ACTIONS$' => $this->deleteMultiRecordGenerator('deleteMultiDatatableBulkActions.stub', $replacements),
            '$DATATABLE_LIMIT$' => $this->moduleAttributes['entries_per_page'],
            '$SORT_CONTROL_VALUE$' => $this->sortControlValueGenerator(),
            '$SORT_CONTROL_OPTIONS$' => $this->sortControlOptionsGenerator(),
            '$CHOICES_MODEL_CONSTANT_VALUES$' => $this->choicesModelGenerator(),
            '$MODEL_APPENDS_COLUMNS$' => $this->modelAppendsGenerator($replacements),
            '$MODEL_MUTATOR_COLUMNS$' => $this->modelMutatorsGenerator($replacements),
            '$SELECT2_PLUGIN_SCRIPTS$' => $this->select2PluginGenerator('scripts', $replacements),
            '$SELECT2_PLUGIN_STYLES$' => $this->select2PluginGenerator('styles', $replacements),
            '$DATE_PICKER_PLUGIN_STYLES$' => $this->datePickerPluginGenerator('styles', $replacements),
            '$DATE_PICKER_PLUGIN_SCRIPTS$' => $this->datePickerPluginGenerator('scripts', $replacements),
            '$TIME_PICKER_PLUGIN_STYLES$' => $this->timePickerPluginGenerator('styles', $replacements),
            '$TIME_PICKER_PLUGIN_SCRIPTS$' => $this->timePickerPluginGenerator('scripts', $replacements),
            '$SOFT_DELETE_ACTION_NULLED_OPEN_CONDITION$' => $this->softDeletesActionsNulledOpenConditionGenerator(),
            '$SOFT_DELETE_ACTION_NULLED_CLOSE_CONDITION$' => $this->softDeletesActionsNulledCloseConditionGenerator(),
            '$FOREIGN_CREATE_CONTROLLER_PLUCK$' => $this->foreignCreateControllerPluckGenerator(),
            '$FOREIGN_CREATE_CONTROLLER_PLUCK_VARIABLE$' => $this->foreignCreateControllerPluckVariableGenerator(),
            '$FOREIGN_EDIT_CONTROLLER_PLUCK$' => $this->foreignCreateControllerPluckGenerator(),
            '$FOREIGN_EDIT_CONTROLLER_PLUCK_VARIABLE$' => $this->foreignCreateControllerPluckVariableGenerator(),
            '$FOREIGN_CONTROLLER_IMPORT_NAMESPACE$' => $this->foreignControllerImportNamespaceGenerator($replacements),
            '$FOREIGN_MODEL_BELONGS_TO$' => $this->foreignModelBelongsToGenerator($replacements),
            '$FOREIGN_MODEL_BELONGS_TO_MANY$' => $this->foreignModelBelongsToManyGenerator($replacements),
            '$FOREIGN_MODEL_IMPORT_NAMESPACE$' => $this->foreignControllerImportNamespaceGenerator($replacements),
            '$FOREIGN_DATATABLE_WITH_QUERY$' => $this->foreignDatatableWithQueryGenerator($replacements),
            '$FOREIGN_UPDATE_OR_STORE_CONTROLLER_BELONGS_TO_MANY_SYNC$' => $this->updateOrStoreControllerbelongsToManySync(),
            '$BELONGS_TO_MANY_MIGRATIONS$' => $this->belongsToManyMigration($replacements),
            '$BELONGS_TO_MANY_MIGRATION_TABLE$' => $this->belongsToManyMigrationTable($replacements),
            '$BELONGS_TO_MANY_STYLES$' => $this->belongsToManyStyles(),
            '$BELONGS_TO_MANY_SCRIPTS$' => $this->belongsToManyScripts(),
            '$UPLOAD_METHODS$' => $this->uploadMethods($replacements),
            '$UPLOAD_CALL_METHODS$' => $this->uploadCallMethods($replacements),
            '$UPLOAD_CONTROLLER_METHOD_MEDIA$' => $this->uploadControllerMethodMedia($replacements),
            '$UPLOAD_MODEL_USE_MEDIA_CLASS$' => $this->uploadModelUseMediaClass(),
            '$UPLOAD_USE_MEDIA_CLASS_MODEL$' => $this->uploadModelImportMediaClassLong(),
            '$UPLOAD_MODEL_USE_MEDIA_CLASS_HAS_MEDIA$' => $this->uploadModelImportMedialClassHasMedia(),
            '$UPLOAD_MODEL_IMPORT_MEDIA_CLASS$' => $this->uploadModelImportMediaClass(),
            '$UPLOAD_MODEL_WITH_MEDIA_RELATION$' => $this->uploadModelWithMedia(),
            '$UPLOAD_MODEL_REGISTER_MEDIA_CONVERSIONS$' => $this->uploadModelRegisterMediaConversions(),
            '$UPLOAD_MODEL_MEDIA_ATTRIBUTES$' => $this->uploadModelMediaAttributes($replacements),
            '$UPLOAD_MODEL_IMPLEMENT_MEDIA$' => $this->uploadModelImplementMedia(),
            '$UPLOAD_DROPZONE_SCRIPT$' => $this->uploadDropzoneScript(),
            '$UPLOAD_DELETE_MEDIA_ROUTE$' => $this->uploadDeleteMediaRoute($replacements),
            '$UPLOAD_FORM_DATA_SCRIPT$' => $this->uploadFormDataScriptCreate($replacements),
            '$UPLOAD_CONTROLLER_MEDIA_DELETE_METHOD$' => $this->uploadControllerMediaDeleteMethod(),
            '$UPLOAD_REQUEST_IMPORT_REPOSITORY$' => $this->uploadRequestImportRepository($replacements),
            '$UPLOAD_REQUEST_CALL_FIND_REPOSITORY$' => $this->uploadRequestCallFindRepository($replacements),
            '$UPLOAD_REQUEST_CHECK_MEDIA_TOTAL$' => $this->uploadRequestCheckMediaTotal($replacements),
            '$API_STORE_COLUMNS$' => $this->apiUpdateOrCreateGenerator('create', $replacements),
            '$API_SHOW_CONTROLLER$' => $this->showRecordGenerator('apiShowFunction.stub', $replacements),
            '$API_EDIT_CONTROLLER$' => $this->apiEditFormGenerator('apiEditController.stub', $replacements),
            '$API_UPDATE_COLUMNS$' => $this->apiUpdateOrCreateGenerator('edit', $replacements),
            '$API_FOREIGN_UPDATE_OR_STORE_CONTROLLER_BELONGS_TO_MANY_SYNC$' => $this->updateOrStoreControllerbelongsToManySync(),
            '$API_UPLOAD_CONTROLLER_METHOD_MEDIA$' => $this->uploadControllerMethodMedia($replacements),
            '$API_CONTROLLER_METHOD_LOAD_RELATION$' => $this->apiControllerMethodLoadRelationGenerator(),
            '$API_DELETE_CONTROLLER$' => $this->deleteRecordGenerator('apiDeleteController.stub', $replacements),
            '$API_ROUTE$' => $this->apiRouteGenerator($replacements),
            '$API_INDEX_COMMENT$' => $this->apiIndexExampleResponseGenerator($replacements),
            '$API_STORE_COMMENT$' => $this->apiStoreExampleResponseGenerator($replacements),
            '$API_SHOW_COMMENT$' => $this->apiShowExampleResponseGenerator($replacements),
            '$API_ERRORS_COMMENT$' => $this->apiErrorsExampleResponseGenerator($replacements),
            '$API_FIRST_ERROR_COMMENT$' => $this->apiErrorsExampleResponseGenerator($replacements, true),
            '$ROUTE_PROVIDER$' => $this->routeProviderGenerator($replacements),
            '$CRUD_LOWER_FIRST$' => $this->crudLowerFirst()
        ];

        return array_merge($replacements, $otherReplacements);
    }   

    /**
     * Get the first crud name as lower case
     * 
     * @return string
     */
    protected function crudLowerFirst() 
    {
        if(isset($this->moduleAttributes['included']['admin']) && $this->moduleAttributes['included']['admin'] == 'on') {
            return 'admin';
        }

        return 'user';
    }
    
    /**
     * Handle getting table relations
     * 
     * @return array
     */
    protected function getTableRelations() 
    {   
        $result = [];

        foreach($this->moduleAttributes['fields'] as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(in_array($type, ForeignFieldTypes::lists())) {
                $result[] = (new ForeignMigrationsGenerator())->getRelatedTableName($field);
            }
        }

        return $result;
    }

    /**
     * Handle getting modified attributes
     * 
     * @param array $attributes
     * 
     * @return array
     */
    protected function modifiedAttributes($attributes) 
    {
        $default = [];
        $fields = $attributes['fields'];
        $separatedFields = ['id', 'created_at', 'updated_at', 'deleted_at'];

        unset($attributes['fields']);

        foreach($fields as $field=>$value) {
            if(in_array($field, $separatedFields)) {
                $default[$field] = $fields[$field];
                unset($fields[$field]);
            }
        }

        $attributes['fields'] = $fields;
        $attributes['default_fields'] = $default;

        return $attributes;
    }

    /**
     * Handle checking is belongs to relationship field
     * 
     * @return boolean
     */
    protected function hasBelongsToRelationshipField() 
    {
        foreach($this->moduleAttributes['fields'] as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(in_array($type, ForeignFieldTypes::lists())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle checking is tinymce field
     * 
     * @return boolean
     */
    protected function hasTinyMCEField() 
    {
        foreach($this->moduleAttributes['fields'] as $field) {
            $field = json_decode($field,true);

            $tinyMCE = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_textarea_tinymce');

            if($tinyMCE == 'on') {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle checking is belongs to many field
     * 
     * @return boolean
     */
    protected function hasBelongsToManyField() 
    {
        foreach($this->moduleAttributes['fields'] as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if($type == ForeignFieldTypes::BELONGS_TO_MANY) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle checking is selected field
     * 
     * @return boolean
     */
    protected function hasSelectField() 
    {
        foreach($this->moduleAttributes['fields'] as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if($type == 'select') {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle checking if has date picker field
     * 
     * @return boolean
     */
    protected function hasDatePickerField() 
    {
        $datePicker = ['date', 'datetime'];
        foreach($this->moduleAttributes['fields'] as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if( in_array($type, $datePicker) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle checking if has a time picker field
     * 
     * @return boolean
     */
    protected function hasTimePickerField() 
    {
        $datePicker = ['time'];
        foreach($this->moduleAttributes['fields'] as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if( in_array($type, $datePicker) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle checking if has upload field
     * 
     * @return boolean
     */
    protected function hasUploadField() 
    {
        foreach($this->moduleAttributes['fields'] as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if( in_array($type, UploadFieldTypes::lists()) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle getting snake date type format
     * 
     * @return string
     */
    protected function getSnakeDateType() 
    {
        //if editing module then get the db value
        if(isset($this->moduleAttributes['id']) && isset($this->moduleAttributes['snake_date'])) {
            return $this->moduleAttributes['snake_date'];
        }

        return date('Y_m_d_His');
    }

    /**
     * Handle getting table model name
     * 
     * @return string
     */
    public function getTableModelName() 
    {
        return Str::snake(Str::pluralStudly($this->getModelName()));
    }

    /**
     * Handle getting model name
     * 
     * @return string
     */
    public function getModelName() 
    {
        return Str::studly(Str::singular($this->moduleAttributes['module_name']));
    }

     /**
     * Handle getting plural model name
     * 
     * @return string
     */
    protected function getPluralModelName() 
    {
        return Str::pluralStudly($this->getModelName());
    }

    /**
     * Handle getting snake model name
     * 
     * @return string
     */
    protected function getSnakeModelName() 
    {
        return Str::snake($this->moduleAttributes['model_name']);
    }

    /**
     * Handle getting kebab model name
     * 
     * @return string
     */
    protected function getKebabModelName() 
    {
        return Str::kebab($this->moduleAttributes['model_name']);
    }

    /**
     * Handle getting upper case first module name
     * 
     * @return string
     */
    public function getStartUpperModule() 
    {
        return ucfirst($this->getLowerModule());
    }

    /**
     * Handle getting camel module name format
     * 
     * @return string
     */
    public function getCamelModule() 
    {
        return Str::camel($this->getStudlyModule());
    }

    /**
     * Handle getting snake module name format
     * 
     * @return string
     */
    public function getSnakeModule() 
    {
        return Str::snake($this->getStudlyModule());
    }

    /**
     * Handle getting kebab module name format
     * 
     * @return string
     */
    public function getKebabModule() 
    {
        return Str::kebab($this->getStudlyModule());
    }

    /**
     * Handle getting plural kebab module name format
     * 
     * @return string
     */
    public function getPluralKebabModule() 
    {
        return Str::plural($this->getKebabModule());
    }

    /**
     * Handle getting studly module name format
     * 
     * @return string
     */
    public function getStudlyModule() 
	{
		return Str::studly($this->name);
	}

    /**
     * Handle getting singular module name format
     * 
     * @return string
     */
    public function getSingularModule() 
    {
        return Str::singular($this->getStudlyModule());
    }

    /**
     * Handle getting singular lower case module name format
     * 
     * @return string
     */
    public function getSingularLowerModule() 
    {
        return strtolower($this->getSingularModule());
    }

    /**
     * Handle getting plural lower case module name format
     * 
     * @return string
     */
    public function getPluralLowerModule() 
    {
        return Str::plural($this->getSingularLowerModule());
    }

    /**
     * Handle getting lowwer case module name format
     * 
     * @return string
     */
	public function getLowerModule() 
	{
		return strtolower($this->getStudlyModule());
	}

    /**
     * Handle getting lower case spaced module name format
     * 
     * @return string
     */
    public function getLowerSpacedModule() 
    {
        return str_replace('-', ' ', str_replace('.', ' ', str_replace('_', ' ', strtolower($this->name))));
    }

    /**
     * Handle getting singular lower case spaced module name format
     * 
     * @return string
     */
    public function getSingularLowerSpacedModule() 
    {
        return Str::singular($this->getLowerSpacedModule());
    }

    /**
     * Handle getting start upper case spaced module name format
     * 
     * @return string
     */
    protected function getStartUpperSpacedModule() 
    {
        return ucfirst($this->getLowerSpacedModule());
    }

    /**
     * Handle getting singular upper case first letter module name format
     * 
     * @return string
     */
    protected function getSingularStartUpperSpacedModule() 
    {
        return Str::singular($this->getStartUpperSpacedModule());
    }

    /**
     * Handle getting plural upper case first letter spaced module name format
     * 
     * @return string
     */
    protected function getPluralStartUpperSpacedModule() 
    {
        return Str::plural($this->getSingularStartUpperSpacedModule());
    }

    /**
     * Handle getting plural lower letter spaced module name format
     * 
     * @return string
     */
    protected function getPluralLowerSpacedModule() 
    {
        return strtolower(Str::plural($this->getSingularStartUpperSpacedModule()));
    }

    /**
     * Handle getting setting module path
     * 
     * @return $this
     */
    protected function setModulePath() 
    {
        $this->modulePath = base_path() . '/Modules/' . $this->getStudlyModule();

        return $this;
    }

    /**
     * Handle generating language form fields
     * 
     * @return string
     */
    protected function languageFormFieldsGenerator() 
    {
        $excluded = ['id', 'created_at', 'updated_at', 'deleted_at'];
        $result = "";
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = (new ModuleGeneratorHelper)
                ->getValueByKey(json_decode($fieldAtt, true), 'field_database_column');

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey(json_decode($fieldAtt, true), 'field_type');

            $tooltip = (new ModuleGeneratorHelper)
                ->getValueByKey(json_decode($fieldAtt, true), 'field_tooltip');

            if(!in_array($field, $excluded)) {
                $visualTitle = (new ModuleGeneratorHelper)
                    ->getValueByKey(json_decode($fieldAtt, true), 'field_visual_title');

    $result .= ',
    "'.$visualTitle.'": "'.$visualTitle.'"';


                if($type == "select" || $type == "belongsToRelationship") {
    $result .= ',
    "Select '.strtolower(Str::of($visualTitle)->singular()).'": "Select '.strtolower(Str::of($visualTitle)->singular()).'"';     
                }

                if($type == "belongsToManyRelationship") {
    $result .= ',
    "Select '.strtolower(Str::of($visualTitle)->plural()).'": "Select '.strtolower(Str::of($visualTitle)->plural()).'"';
                }

                if($tooltip != "") {
    $result .= ',
    "'.$tooltip.'": "'.$tooltip.'"';
                }
    
            }
        }

        return $result;
    }

    /**
     * Handle generating form
     * 
     * @param string $type
     * @param array $moduleReplaceements
     * 
     * @return string
     */
    protected function formGenerator($type, $moduleReplacements) 
    {
        $content = '';
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {
            $content .= (new FormGenerator)->generate(
                $type,
                json_decode($fieldAtt,true), 
                new TextForm,
                $moduleReplacements
            );
        }

        return $content;
    }

    /**
     * Handle generating datatable
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function datatableGenerator($moduleReplacements) 
    {
        $content = '';
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $inList = (new ModuleGeneratorHelper)
                ->getValueByKey(json_decode($fieldAtt,true), 'in_list');

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey(json_decode($fieldAtt,true), 'field_type');

            if($inList == 'on' && $type != 'password') {
                $content .= (new DatatableGenerator)->generate(
                    json_decode($fieldAtt,true),
                    $moduleReplacements
                );
            }
        }

        return $content;
    }

    /**
     * Handle generating migrations
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function migrationsGenerator($moduleReplacements) 
    {
        $content = '';
        $cntr = 0;

        $fields = $this->modifiedAttributes($this->moduleAttributes);

        foreach($fields['fields'] as $key=>$fieldAtt) {

            $content .= (new MigrationsGenerator)->generate(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );
            
            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating foreign field migrations
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function foreignMigrationsGenerator($moduleReplacements) 
    {
        $content = '';
        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $content .= (new ForeignMigrationsGenerator)->generate(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating model fillable property
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    public function fillableGenerator($moduleReplacements) 
    {
        $excluded = array_merge([ForeignFieldTypes::BELONGS_TO_MANY], UploadFieldTypes::lists());
        $content = '';
        $cntr = 0;

        $fields = $this->modifiedAttributes($this->moduleAttributes);

        foreach($fields['fields'] as $key=>$fieldAtt) {

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey(json_decode($fieldAtt,true), 'field_type');

            if(!in_array($type, $excluded)) {
                $content .= (new FillableGenerator)->generate(
                    json_decode($fieldAtt,true),
                    $moduleReplacements,
                    ForeignFieldTypes::lists(),
                    $this->foreignFieldSuffix,
                    $cntr
                );

                $cntr++;
            }
            
        }

        return $content;
    }

    /**
     * Handle generating model hidden property
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    public function hiddenGenerator($moduleReplacements) 
    {
        return (new HiddenGenerator)->generate($this->moduleAttributes['fields'], $moduleReplacements);
    }

    /**
     * Handle generating repository searchable property
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    public function searchableGenerator($moduleReplacements) 
    {
        $content = '';
        $cntr = 0;

        $fields = $this->modifiedAttributes($this->moduleAttributes);

        foreach($fields['fields'] as $key=>$fieldAtt) {

            $content .= (new SearchableGenerator)->generate(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                ForeignFieldTypes::lists(),
                $this->foreignFieldSuffix,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating repository orderable property
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    public function orderableGenerator($moduleReplacements) 
    {
        $content = '';
        $cntr = 0;

        $fields = $this->modifiedAttributes($this->moduleAttributes);

        foreach($fields['fields'] as $key=>$fieldAtt) {

            $content .= (new OrderableGenerator)->generate(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                ForeignFieldTypes::lists(),
                $this->foreignFieldSuffix,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating repository updateOrCreate method
     * 
     * @param string $type
     * @param array $moduleReplacements
     * 
     * @return string
     */
    public function updateOrCreateGenerator($type, $moduleReplacements) 
    {
        $content = '';
        $cntr = 0;

        $excluded = array_merge([ForeignFieldTypes::BELONGS_TO_MANY], UploadFieldTypes::lists());

        $fields = $this->modifiedAttributes($this->moduleAttributes);

        // total fields that turned on
        $totalFields = $this->countInType($fields['fields'], $type);

        foreach($fields['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt, true);

            $fieldName = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_database_column');

            $inType = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'in_'.$type);

            $fieldType = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            // exclude belongs to many on lists
            // if($inType == 'on' && !in_array($fieldType, $excluded)) {
            if(!in_array($fieldType, $excluded)) {

                $foreignFieldSuffix = '';

                if(in_array($fieldType, ForeignFieldTypes::lists())) {
                    $foreignFieldSuffix = $this->foreignFieldSuffix;
                }

                $content .= " '$fieldName$foreignFieldSuffix',";
                
            }

            $cntr++;
        }

        // check if last has , character
        if(substr($content, -1) == ',') {
            $content = trim(substr_replace($content, "", -1));
            
            return $content == "" || $content == null || empty($content) ? "[]" : $content;
        }

        $content = trim($content);

        return $content == "" || $content == null || empty($content) ? "[]" : $content;
    }

    /**
     * Count in {type} that turned on
     * 
     * @param array $fields Array from request with fields; field inside need to decode
     * @param string $type (type - show, create, edit, list)
     * 
     * @return integer|0
     */
    protected function countInType($fields, $type) 
    {
        $counter = 0;
        foreach($fields as $field) {
            $field = json_decode($field,true);

            $inType = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'in_'.$type);

            if($inType == 'on') {
                $counter++;
            }
        }

        return $counter;
    }

    /**
     * Handle generating validation
     * 
     * @param string $type
     * @param array $moduleReplacements
     * 
     * @return string
     */
    public function validationGenerator($type, $moduleReplacements) 
    {
        $content = '';
        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $content .= (new RequestGenerator)->generate(
                $type,
                $field,
                $moduleReplacements,
                ForeignFieldTypes::lists(),
                $this->foreignFieldSuffix,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating tinymce config script
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function tinyMCEConfigScript($moduleReplacements) 
    {
        if($this->hasTinyMCEField()) {
            return (new TinyMCEConfigScriptGenerator)->generate($moduleReplacements);
        }
    }

    /**
     * Handle generating tinymce init script
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function tinyMCEInitScript($moduleReplacements) 
    {
        if($this->hasTinyMCEField()) {
            return (new TinyMCEInitScriptGenerator)->generate($moduleReplacements);
        }
    }

    /**
     * Handle generating ajax data script
     * 
     * @param string $type
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function getAjaxDataScript($type, $moduleReplacements) 
    {
        $content = (new GetAjaxDataScriptGenerator)->generate('no_tinymce', $moduleReplacements);;

        if($this->hasTinyMCEField()) {
            $content = (new GetAjaxDataScriptGenerator)->generate('with_tinymce', $moduleReplacements); 
        }

        return str_replace('$AJAX_OPERATION_TYPE$', $type, $content);
    }

    /**
     * Handle generating tinymce plugin script
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function tinyMCEPluginScript($moduleReplacements) 
    {
        if($this->hasTinyMCEField()) {
            return (new TinyMCEPluginScriptGenerator)->generate($moduleReplacements);
        }
    }

    /**
     * Handle generating migrations soft delete
     * 
     * @return string
     */
    protected function migrationsSoftDeleteGenerator() 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return '
            $table->softDeletes();';
        }
    }

    /**
     * Handle generating import soft delete to the model
     * 
     * @return string
     */
    protected function softDeletesModelUseGenerator() 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return '
use Illuminate\Database\Eloquent\SoftDeletes;';
        }
    }

    /**
     * Handle generating soft delete model import
     * 
     * @return string
     */
    protected function softDeletesModelImportGenerator() 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return 'SoftDeletes, ';
        }
    }

    /**
     * Handle generating soft delete filter script table in datatable
     * 
     * @return string
     */
    protected function softDeletesFilterTabsGenerator() 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return "
        filterTabs: [
            { label: app.trans('All'), filters: [{ key: 'status', value: 'All' }] },
            { label: app.trans('Trashed'), filters: [{ key: 'status', value: 'Trashed' }] }
        ],";
        }
    }

    /**
     * Handle generating soft delete datatable filter control
     * 
     * @return string
     */
    protected function softDeletesFilterControlGenerator() 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return "
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('All'), value: 'All' },
                    { label: app.trans('Trashed'), value: 'Trashed' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            }
        ],";
        }
    }

    /**
     * Handle generating soft delete function script in datatable
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function softDeletesFunctionsScriptGenerator($moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return (new SoftDeleteFunctionsScriptGenerator)->generate($moduleReplacements);
        }
    }

    /**
     * Handle generating soft delete config route
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function softDeletesConfigRoutesGenerator($moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return (new SoftDeleteConfigRoutesGenerator)->generate($moduleReplacements);
        }
    }

    /**
     * Handle generating soft delete action script in datatable
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function softDeletesActionsGenerator($moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return (new SoftDeleteActionsGenerator)->generate($moduleReplacements);
        }
    }

    /**
     * Handle generating soft delete init script in datatable
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function softDeletesInitScriptsGenerator($moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return (new SoftDeleteInitScriptsGenerator)->generate($moduleReplacements);
        }
    }

    /**
     * Handle generating soft delete controller method
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function softDeleteControllerFunctionsGenerator($moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return (new SoftDeleteControllerFunctionsGenerator)->generate($moduleReplacements);
        }
    }

    /**
     * Handle generating soft delete route
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function softDeleteRoutesGenerator($moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return (new SoftDeleteRoutesGenerator)->generate($moduleReplacements);
        }
    }

    /**
     * Handle generating create form
     * 
     * @param string $stub
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function createFormGenerator($stub, $moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['create_form']) 
            && $this->moduleAttributes['included']['create_form'] == 'on') {
            return (new CreateFormGenerator)->generate($stub, $moduleReplacements);
        }
    }

    /**
     * Handle generating edit form
     * 
     * @param string $stub
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function editFormGenerator($stub, $moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['edit_form']) 
            && $this->moduleAttributes['included']['edit_form'] == 'on') {
            return (new EditFormGenerator)->generate($stub, $moduleReplacements);
        }
    }

    /**
     * Handle generating view field
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function viewFieldGenerator($moduleReplacements) 
    {
        $content = '';
        if(isset($this->moduleAttributes['included']['show_page']) 
            && $this->moduleAttributes['included']['show_page'] == 'on') {

            foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

                $field = json_decode($fieldAtt, true);

                $inShow = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'in_show');

                if($inShow == 'on') {
                    $content .= (new ViewFieldGenerator)->generate(
                        $field,
                        $moduleReplacements
                    );
                }
                
            }

        }

        return $content;
    }

    /**
     * Handle generating show record
     * 
     * @param string $stub
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function showRecordGenerator($stub, $moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['show_page']) 
            && $this->moduleAttributes['included']['show_page'] == 'on') {
            return (new ShowRecordGenerator)->generate($stub, $moduleReplacements);
        }
    }

    /**
     * Handle generating delete record
     * 
     * @param string $stub
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function deleteRecordGenerator($stub, $moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['delete_action']) 
            && $this->moduleAttributes['included']['delete_action'] == 'on') {
            return (new DeleteRecordGenerator)->generate($stub, $moduleReplacements);
        }
    }

    /**
     * Handle generating delete multi record
     * 
     * @param string $stub
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function deleteMultiRecordGenerator($stub, $moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['multi_delete_action']) 
            && $this->moduleAttributes['included']['multi_delete_action'] == 'on') {
            return (new DeleteMultiRecordGenerator)->generate($stub, $moduleReplacements);
        }
    }

    /**
     * Handle generating sort control value for datatable
     * 
     * @return string
     */
    protected function sortControlValueGenerator() 
    {
        if(isset($this->moduleAttributes['order_by_column']) && isset($this->moduleAttributes['order_by_value'])) {
            return $this->moduleAttributes['order_by_column'] . '__' . $this->moduleAttributes['order_by_value'];
        }

        return 'id__desc';
    }

    /**
     * Handle generating sort control options for datatable
     * 
     * @return string
     */
    protected function sortControlOptionsGenerator() 
    {
        $content = '';
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt, true);

            $isSortable = (new ModuleGeneratorHelper)->getValueByKey($field, 'is_sortable');
            $fieldName = (new ModuleGeneratorHelper)->getValueByKey($field, 'field_database_column');
            $fieldTitle = (new ModuleGeneratorHelper)->getValueByKey($field, 'field_visual_title');

            if($isSortable == 'on') {
                $content .= ",
                { value: '".$fieldName."__asc', label: app.trans('".$fieldTitle."') + ' ( A - Z )' },
                { value: '".$fieldName."__desc', label: app.trans('".$fieldTitle."') + ' ( Z - A )' }";
            }
        }

        return $content;
    }

    /**
     * Handle generating field choices
     * 
     * @param string $field
     * 
     * @return string
     */
    protected function getFieldChoices($field) 
    {   
        $choiceDBName = 'choices_database_value';
        $choiceLabelText = 'choices_label_text';

        $dataArray = [];

        foreach($field as $data) {
            if (strpos($data['name'], $choiceDBName) !== false) {

                preg_match_all('/\[(.*?)\]/s', $data['name'], $match);

                if(isset($match[1]) && isset($match[1][0])) {
                    $index = $match[1][0];

                    $dataArray[$index]['database_value'] = $data['value'];
                }
            }


            if (strpos($data['name'], $choiceLabelText) !== false) {

                preg_match_all('/\[(.*?)\]/s', $data['name'], $match);

                if(isset($match[1]) && isset($match[1][0])) {
                    $index = $match[1][0];

                    $dataArray[$index]['label_text'] = $data['value'];
                }
            }
        }

        return $dataArray;
    }

    /**
     * Handle generating field choices
     * 
     * @param string $field
     * 
     * @return string
     */
    protected function choicesModelGenerator() 
    {
        $types = ['radio', 'select'];

        $content = '';
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt, true);

            $choices = $this->getFieldChoices($field);

            $inType = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            $fieldName = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_database_column');

            if(in_array($inType, $types)) {
                $content .= (new ChoicesModelGenerator($fieldName, $inType, $choices))->generate();
            }
            
        }

        return $content;
    }

    /**
     * Handle generating model appends property
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function modelAppendsGenerator($moduleReplacements) 
    {   
        $result = '';
        $content = '';
        $start = '

    /**
     * @var array $appends
     */
    protected $appends = [';
        $end = '
    ];';
        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $content .= (new ModelAppendsGenerator)->generate(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );

            $cntr++;
        }

        if($content != '') {
            $result .= $start;
            $result .= $content;
            $result .= $end;
        }

        return $result;
    }

    /**
     * Handle generating model mutators
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function modelMutatorsGenerator($moduleReplacements) 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $content .= (new ModelMutatorsGenerator)->generate(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating select2 plugin
     * 
     * @param string $type
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function select2PluginGenerator($type, $moduleReplacements) 
    {
        if($this->hasSelectField()) {
            return (new Select2PluginGenerator)->generate($type, $moduleReplacements);
        }
    }

    /**
     * Handle generating datepicker plugin
     * 
     * @param string $type
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function datePickerPluginGenerator($type, $moduleReplacements) 
    {
        if($this->hasDatePickerField()) {
            return (new DatePickerPluginGenerator)->generate($type, $moduleReplacements);
        }
    }

    /**
     * Handle generating timepicker plugin
     * 
     * @param string $type
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function timePickerPluginGenerator($type, $moduleReplacements) 
    {
        if($this->hasTimePickerField()) {
            return (new TimePickerPluginGenerator)->generate($type, $moduleReplacements);
        }
    }

    /**
     * Handle generating soft delete actions nulled open for datatable
     * 
     * @return string
     */
    protected function softDeletesActionsNulledOpenConditionGenerator() 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return "
                        if(row.deleted_at === null) 
                        {";
        }
    }

    /**
     * Handle generating soft delete actions nulled close for datatable
     * 
     * @return string
     */
    protected function softDeletesActionsNulledCloseConditionGenerator() 
    {
        if(isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on') {
            return "
                        }";
        }
    }
    
    /**
     * Handle generating foreign create method pluck for controller
     * 
     * @return string
     */
    protected function foreignCreateControllerPluckGenerator() 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(in_array($type, ForeignFieldTypes::lists())) {

                $fieldDatabaseColumn = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'field_database_column');

                $namespace = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'related_model');

                $fieldShow = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'field_show');

                $var = lcfirst(Str::studly(Str::plural($fieldDatabaseColumn)));

                $model = (new ModelByNamespace($namespace))->get();

                $content .= '$'.$var." = $model::pluck('".$fieldShow."', 'id');
        
        ";
                $cntr++;
            }
            
        }

        return $content;
    }

    /**
     * Handle generating variable for create controller foreign pluck
     * 
     * @return string
     */
    protected function foreignCreateControllerPluckVariableGenerator() 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(in_array($type, ForeignFieldTypes::lists())) {

                $fieldDatabaseColumn = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'field_database_column');

                $var = lcfirst(Str::studly(Str::plural($fieldDatabaseColumn)));

                $content .= ",
            '$var' => $".$var;

                $cntr++;
            }
            
        }

        return $content;
    }

    /**
     * Handle generating foreign controller import namespace
     * 
     * @param array $replacements
     * 
     * @return string
     */
    protected function foreignControllerImportNamespaceGenerator($replacements) 
    {
        $existingNamespace = [];
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(in_array($type, ForeignFieldTypes::lists())) {

                $namespace = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'related_model');

                $slash = substr($namespace, 0, 1);

                if($slash == '\\') {
                    $namespace = substr($namespace, 1);
                }

                if(!in_array($namespace, $existingNamespace)) {
                    $existingNamespace[] = $namespace;
                    $content .= 'use ' . $namespace . ';
';
                }
                
                $cntr++;
            }
            
        }

        return $content;
    }

    /**
     * Handle generating model belongs to method for foreign
     * 
     * @param array $replacements
     * 
     * @return string
     */
    protected function foreignModelBelongsToGenerator($replacements) 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if($type == ForeignFieldTypes::BELONGS_TO) {

                $fieldName = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'field_database_column');

                $namespace = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'related_model');

                $model = (new ModelByNamespace($namespace))->get();

                $content .= "

    public function ".$fieldName."()
    {
        return ".'$this->belongsTo'."(".Str::studly($model)."::class, '".Str::snake($fieldName)."_id');
    }";

                $cntr++;
            }
            
        }

        return $content;
    }

    /**
     * Handle generating model belongs to many for foreign
     * 
     * @param array $replacements
     * 
     * @return string
     */
    protected function foreignModelBelongsToManyGenerator($replacements) 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if($type == ForeignFieldTypes::BELONGS_TO_MANY) {

                $fieldName = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'field_database_column');

                $namespace = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'related_model');

                $model = (new ModelByNamespace($namespace))->get();

                $tableName = (new BelongsToManyMigrationGenerator)->tableName($field, $replacements);

                $foreignFieldName = (new BelongsToManyMigrationGenerator)->foregnId($field, $replacements);

                $relatedFieldName = (new BelongsToManyMigrationGenerator)->relatedid($field, $replacements);

                $content .= "

    public function ".$fieldName."()
    {
        return ".'$this->belongsToMany'."(
            ".Str::studly($model)."::class, 
            '".$tableName."', 
            '".$foreignFieldName."',
            '".$relatedFieldName."'
        );
    }";

                $cntr++;
            }
            
        }

        return $content;
    }

    /**
     * Handle generating foreign with() method query for datatable
     * 
     * @param array $replacements
     * 
     * @return string
     */
    protected function foreignDatatableWithQueryGenerator($replacements) 
    {
        $content = '';
        $separator = '';
        $cntr = 0;

        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(in_array($type, ForeignFieldTypes::lists())) {

                $fieldName = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'field_database_column');

                if($cntr > 0) {
                    $separator = ', ';
                }

                $content .= "$separator'".$fieldName."'";

                $cntr++;
            }
            
        }

        $finalContent = '';

        if($content != '') {
            $finalContent = '->with(['.$content.'])';
        }

        return $finalContent;
    }

    /**
     * Handle generating updateOrStore method on controller for sync belongs to many
     * 
     * @return string
     */
    protected function updateOrStoreControllerbelongsToManySync() 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if($type == ForeignFieldTypes::BELONGS_TO_MANY) {

                $fieldName = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'field_database_column');

                $requestFieldName = lcfirst(Str::studly(Str::plural($fieldName)));

                $content .= "

        ".'$'."model->".$fieldName."()->sync(".'$'."request->input('".$fieldName."', []));";

                $cntr++;
            }
            
        }

        return $content;
    }

    /**
     * Handle generating belongs to many migration
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function belongsToManyMigration($moduleReplacements) 
    {
        if($this->hasBelongsToManyField()) {

            $migrations = (new BelongsToManyMigrationGenerator)->generate(
                $this->moduleAttributes['fields'], 
                $moduleReplacements
            );

            $this->storeTableNames($migrations['table_names']);

            return $migrations['result'];

        }
        
        return '';
    }

    /**
     * Handle generating belongs to many migration table
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function belongsToManyMigrationTable($moduleReplacements) 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if($type == ForeignFieldTypes::BELONGS_TO_MANY) {

                $tableName = (new BelongsToManyMigrationGenerator)->tableName($field, $moduleReplacements);

                $content .= "
        Schema::dropIfExists('$tableName');";

                $cntr++;
            }
            
        }

        return $content;
    }

    /**
     * Handle generating belongs to many styles
     * 
     * @return string
     */
    protected function belongsToManyStyles() 
    {
        if($this->hasBelongsToRelationshipField()) {
            return "";
        }
    }

    /**
     * Handle generating belongs to many scripts
     * 
     * @return string
     */
    protected function belongsToManyScripts() 
    {
        if($this->hasBelongsToRelationshipField()) {
            return "";
        }
    }

    /**
     * Handle generating upload methods
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function uploadMethods($moduleReplacements) 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $content .= (new UploadGenerator)->generateMethod(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating upload call methods
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function uploadCallMethods($moduleReplacements) 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $content .= (new UploadGenerator)->generateCallMethod(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating upload method media
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function uploadControllerMethodMedia($moduleReplacements) {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $content .= (new UploadGenerator)->generateControllerMethodMedia(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating upload model use media class
     * 
     * @return string
     */
    protected function uploadModelUseMediaClass() 
    {
        if($this->hasUploadField()) {
            return ', InteractsWithMedia, MediaHelper';
        }
    }

    /**
     * Handle generating upload model import media class
     * 
     * @return string
     */
    protected function uploadModelImportMediaClass() 
    {
        if($this->hasUploadField()) {
            return '
use Modules\Base\Support\Media\MediaHelper;
use Spatie\MediaLibrary\InteractsWithMedia;';
        }
    }

    /**
     * Don't modify this is use by controller
     */
    protected function uploadModelImportMediaClassLong() 
    {
        if($this->hasUploadField()) {
            return '
use Spatie\MediaLibrary\MediaCollections\Models\Media;';
        }
    }

    /**
     * Handle generating upload model import has media class
     * 
     * @return string
     */
    protected function uploadModelImportMedialClassHasMedia() 
    {
        if($this->hasUploadField()) {
            return '
use Spatie\MediaLibrary\HasMedia;';
        }
    }

     /**
     * Handle generating upload model with media property
     * 
     * @return string
     */
    protected function uploadModelWithMedia() 
    {
        if($this->hasUploadField()) {
            return "

    /**
     * @var array ".'$'."with
     */
    protected ".'$'."with = ['media'];";
        }
    }

     /**
     * Handle generating upload model on registering media conversion method
     * 
     * @return string
     */
    protected function uploadModelRegisterMediaConversions() 
    {
        if($this->hasUploadField()) {
            return "

    /**
     * Handle on registering media conversions
     *
     * @param Media|null ".'$'."media
     *
     * @return void
     */
    public function registerMediaConversions(Media ".'$'."media = null): void
    {
        ".'$'."this->addMediaConversion('thumb')->fit('crop', 50, 50);
        ".'$'."this->addMediaConversion('preview')->fit('crop', 120, 120);
    }";
        }
    }

    /**
     * Handle generating upload model media attributes
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function uploadModelMediaAttributes($moduleReplacements) {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $content .= (new UploadGenerator)->generateModelMediaAttributes(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating upload model implement media class
     * 
     * @return string
     */
    protected function uploadModelImplementMedia() 
    {
        if($this->hasUploadField()) {
            return ' implements HasMedia';
        }
    }

    /**
     * Handle generating upload dropzone script
     * 
     * @return string
     */
    protected function uploadDropzoneScript() 
    {
        if($this->hasUploadField()) {
            return "
    <script type=\"text/javascript\" src=\"{{ url('plugins/dropzone/js/dropzone.min.js') }}\"></script>";
        }
    }

    /**
     * Handle generating upload delete media route
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function uploadDeleteMediaRoute($moduleReplacements) 
    {
        if($this->hasUploadField()) {
            return (new UploadGenerator)->generateRoute($moduleReplacements);
        }
    }

    /**
     * Handle generating upload form data script on create page
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function uploadFormDataScriptCreate($moduleReplacements) {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $content .= (new UploadGenerator)->generateFormDataScriptCreate(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating upload controller media delete method
     * 
     * @return string
     */
    protected function uploadControllerMediaDeleteMethod() 
    {
        if($this->hasUploadField()) {
            return '
    
    /**
     * Handle on removing media
     *
     * @param Request $request
     *
     * @return void
     */
    public function removeMedia(Request $request) 
    {
        $this->authorize(' . "'" .'$CRUD_LOWER_END_DOT$$' . 'PLURAL_KEBAB_NAME' . '$.remove-media' . "'" .');

        $media = Media::where(\'uuid\', $request->get(\'uid\'))->first();

        $media->delete();

        return $this->successResponse(__(\'Media deleted successfully.\'));
    }';
        }
        
    }

    /**
     * Handle generating upload request import repository
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function uploadRequestImportRepository($moduleReplacements) 
    {
        if($this->hasUploadField()) {
            return (new UploadGenerator)->generateRequestImportRepository($moduleReplacements);
        }
    }

    /**
     * Handle generating upload request call find repository
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function uploadRequestCallFindRepository($moduleReplacements) 
    {
        if($this->hasUploadField()) {
            return (new UploadGenerator)->generateRequestCallFindRepository($moduleReplacements);
        }
    }

    /**
     * Handle generating upload request check media total
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function uploadRequestCheckMediaTotal($moduleReplacements) 
    {
        $content = '';

        $cntr = 0;
        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $content .= (new UploadGenerator)->generateRequestCheckMediaTotal(
                json_decode($fieldAtt,true),
                $moduleReplacements,
                $cntr
            );

            $cntr++;
        }

        return $content;
    }

    /**
     * Handle generating tinymce form data script
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function tinyMCEFormDataScript($moduleReplacements) {
        
        if($this->hasTinyMCEField()) {
            return '

        $.each($(\'.$CRUD_LOWER_END_DASH$'.$moduleReplacements['$KEBAB_NAME$'].'-tinymce-default\'), function() {
            data.append($(this).attr(\'name\'), tinyMCE.get($(this).attr(\'id\')).getContent());
        });';
        }
    }

    /**
     * Handle generating api update or create method
     * 
     * @param string $type
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function apiUpdateOrCreateGenerator($type, $moduleReplacements) 
    {
        return $this->updateOrCreateGenerator($type, $moduleReplacements);
    }

    /**
     * Handle generating api edit form
     * 
     * @param string $stub
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function apiEditFormGenerator($stub, $moduleReplacements) 
    {
        return $this->editFormGenerator($stub, $moduleReplacements);
    }

    /**
     * Handle generating api controller method and load the relation
     * 
     * @return string
     */
    protected function apiControllerMethodLoadRelationGenerator() 
    {
        $content = '';
        $separator = '';
        $cntr = 0;

        foreach($this->moduleAttributes['fields'] as $key=>$fieldAtt) {

            $field = json_decode($fieldAtt,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(in_array($type, ForeignFieldTypes::lists())) {

                $fieldName = (new ModuleGeneratorHelper)
                    ->getValueByKey($field, 'field_database_column');

                if($cntr > 0) {
                    $separator = ', ';
                }

                $content .= "$separator'".$fieldName."'";

                $cntr++;
            }
            
        }

        $finalContent = '';

        if($content != '') {
            $finalContent = '

        $model->load(['.$content.']);';
        }

        return $finalContent;
    }

    /**
     * Handle generating api route
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    public function apiRouteGenerator($moduleReplacements) 
    {
        if(isset($this->moduleAttributes['included']['generate_api_crud']) 
            && $this->moduleAttributes['included']['generate_api_crud'] == 'on') {
            $content = 'Route::group([\'namespace\' => \'\Modules\$STUDLY_NAME$\Http\Controllers\Api\V1\'], function () {

    Route::group([\'prefix\' => \'v1\', \'as\' => \'admin.\'], function () {

        Route::group([\'middleware\' => [\'auth:sanctum\', \'verified\']], function() {
            Route::apiResource(\'$PLURAL_KEBAB_NAME$\', \'$STUDLY_NAME$Controller\')->parameters([
                \'$PLURAL_KEBAB_NAME$\' => \'id\'
            ]);;
        });

    });
    
});';

            return (new ModuleGeneratorHelper)->replace($moduleReplacements, $content);
        }
    }

    /**
     * Handle generating API example response for index method
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function apiIndexExampleResponseGenerator($moduleReplacements) 
    {
        return (new ApiExampleResponse)->index($this->modifiedAttributes($this->moduleAttributes), $moduleReplacements, $this->foreignFieldSuffix);
    }

    /**
     * Handle generating API example response for store method
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function apiStoreExampleResponseGenerator($moduleReplacements) 
    {
        return (new ApiExampleResponse)->store($this->modifiedAttributes($this->moduleAttributes), $moduleReplacements, $this->foreignFieldSuffix);
    }

    /**
     * Handle generating API example response for show method
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function apiShowExampleResponseGenerator($moduleReplacements) 
    {
        return (new ApiExampleResponse)->show($this->modifiedAttributes($this->moduleAttributes), $moduleReplacements, $this->foreignFieldSuffix);
    }

    /**
     * Handle generating API example response for errors
     * 
     * @param array $moduleReplacements
     * @param bool $firstError
     * 
     * @return string
     */
    protected function apiErrorsExampleResponseGenerator($moduleReplacements, $firstError=false) 
    {
        return (new ApiExampleResponse)->errors($this->modifiedAttributes($this->moduleAttributes), $moduleReplacements, $this->foreignFieldSuffix, $firstError);
    }

    /**
     * Handle generating route provider generator
     * 
     * @param array $moduleReplacements
     * 
     * @return string
     */
    protected function routeProviderGenerator($moduleReplacements) 
    {   
        $content = '';
        $cruds = [];

        if(isset($this->moduleAttributes['included']['admin']) && $this->moduleAttributes['included']['admin'] == 'on') {
            $cruds[] = 'admin';
        }

        if(isset($this->moduleAttributes['included']['user']) && $this->moduleAttributes['included']['user'] == 'on') {
            $cruds[] = 'user';
        }

        foreach($cruds as $crud) {
            $replacements = array_merge($moduleReplacements, CrudTypeReplacements::lists($crud));

            $content .= (new RouteProviderGenerator)->generate($replacements);
        }

        return $content;
    }

    /**
     * Handle storing module table names
     * 
     * @var array $tables
     * 
     * @return void
     */
    protected function storeTableNames($tables = []) 
    {   
        foreach($tables as $table) {
            if(isset($this->moduleAttributes['table_names'])) {
                if(!in_array($table, $this->moduleAttributes['table_names'])) {
                    $this->moduleAttributes['table_names'][] = $table;
                }
            } else {
                $this->moduleAttributes['table_names'][] = $table;
            }
        }
    }

    /**
     * Handle appending module route name to attributes
     * 
     * @var array $routeName
     * 
     * @return void
     */
    protected function appendRouteName($routeName) 
    {
        if(isset($routeName[0])) {
            $this->moduleAttributes['route_name'] = $routeName[0];
        }
    }

    /**
     * Handle appending snake date and saved it to the database for fixing issue of migration file naming when rebuilding it
     * 
     * @return void
     */
    protected function appendSnakeDate() 
    {
        $this->moduleAttributes['snake_date'] = $this->getSnakeDateType();
    }

    /**
     * Handle deleting previous migrations
     * - Fixes for the same file name of migrations
     * 
     * @return void
     */
    protected function deletePreviousMigrations() 
    {
        $replacements = $this->replacements();

        $migration = $replacements['$SNAKE_DATE$'] . '_create_' . $replacements['$TABLE_MODEL$'] . '_table';
        $migration = DB::table('migrations')->where('migration', $migration);

        if(!is_null($migration->first())) {
            $migration->delete();
        }
    }
}
