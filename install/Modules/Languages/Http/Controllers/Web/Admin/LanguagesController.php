<?php

namespace Modules\Languages\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Base\Models\Country;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Modules\Menus\Support\MenuDuplicator;
use Modules\Languages\Events\LanguagesSync;
use Illuminate\Contracts\Support\Renderable;
use Modules\Languages\Events\LanguagesCreated;
use Modules\Languages\Events\LanguagesDeleted;
use Modules\Languages\Events\LanguagesUpdated;
use Modules\Users\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Base\Support\Language\Translations;
use Modules\Languages\Events\LanguagesRestored;
use Modules\Languages\Services\GoogleTranslate;
use Modules\Languages\Events\LanguagesForceDeleted;
use Modules\Languages\Events\LanguagesPhraseCreated;
use Modules\Languages\Events\LanguagesPhraseUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Languages\Repositories\LanguagesRepository;
use Modules\Languages\Http\Requests\StoreLanguageRequest;
use Modules\Languages\Http\Requests\UpdateLanguageRequest;
use Modules\Languages\Http\Requests\AddLanguagePhraseRequest;
use Modules\Languages\Http\Requests\UpdateLanguagePhraseRequest;
use Modules\Languages\Http\Requests\TranslateLanguagePhraseRequest;

class LanguagesController extends BaseController
{   
    /**
     * @var Filesytem $file
     */
    protected $file;

    /**
     * @var string $defaultCode
     */
    protected $defaultCode = 'en';

    /**
     * @var LanguagesRepository $languages
     */
    protected $languages;

    /**
     * @var UserRepository $users
     */
    protected $users;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/languages';

    public function __construct(LanguagesRepository $languages, Filesystem $file, UserRepository $users) 
    { 
        $this->file = $file;
        $this->languages = $languages;
        $this->users = $users;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.languages.index');

        $languages = $this->languages->getExcludeEnglish();

        return view('languages::admin.index', [
            'pageTitle' => __('Global System Languages'),
            'languages' => $languages,
            'addLanguagePhraseRoute' => route('admin.languages.add-language-phrase'),
            'autoTranslateAddingPhrase' => route('admin.languages.auto-translate-adding-phrase'),
            'policies' => JsPolicy::get('languages')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.languages.create');

        $flags = Country::pluck('name', 'id');
        
        return view('languages::admin.create', [
            'pageTitle' => __('Create new language'),
            'flags' => $flags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreLanguageRequest $request
     * @return JsonResponse
     */
    public function store(StoreLanguageRequest $request)
    {
        $translations = new Translations;
        
        $code = strtolower($request->get('code'));

        $translations->createDefaultByCode($code);

        $model = $this->languages->create($request->only(
            'title', 
            'code', 
            'direction', 
            'flag_id', 
            'active'
        ));

        // Duplicate menus with the created language
        (new MenuDuplicator($request->get('code')))->execute();

        event(new LanguagesCreated($model));

        return $this->handleAjaxRedirectResponse(
            __('Language created successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $this->authorize('admin.languages.show');

        return view('languages::admin.show', [
            'pageTitle' => __('Show language'),
            'languages' => $this->languages->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.languages.edit');

        $flags = Country::pluck('name', 'id');
        
        return view('languages::admin.edit', [
            'pageTitle' => __('Edit language'),
            'languages' => $this->languages->findOrFail($id),
            'flags' => $flags
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateLanguageRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateLanguageRequest $request, $id)
    {
        $model = $this->languages->findOrFail($id);

        $this->languages
            ->update($model, 
                $request->only('title', 'code', 'direction', 'flag_id', 'active'));

        event(new LanguagesUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Language updated successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function editCode($id, $code)
    {
        $this->authorize('admin.languages.edit-code');

        $flags = Country::pluck('name', 'id');

        return view('languages::admin.edit-lang', [
            'pageTitle' => __('Edit language'),
            'languages' => $this->languages->findOrFail($id),
            'flags' => $flags
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.languages.delete');

        $language = $this->languages->findOrFail($request->id);

        if($language->code == setting('locale')) {
            return $this->errorResponse(
                __("Default language couldn't be deleted.")
            );
        }

        $this->languages->delete($language);
        
        // Update users langauge to default after deleted the language they are using
        $this->users->updateUsersLocaleToDefault($language->code);

        event(new LanguagesDeleted($this->languages, $request->id));
        
        return $this->handleAjaxRedirectResponse(
            __('Language deleted successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Multi Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function multiDestroy(Request $request)
    {
        $this->authorize('admin.languages.multi-delete');

        $affectedLanguages = $this->languages->getByIds($request->ids);

        foreach($affectedLanguages as $language) {
            if($language->code == setting('locale')) {
                return $this->errorResponse(
                    __("One of the language couldn't be deleted. Please remove :language language first before to continue.", ['language' => strtolower($language->title)])
                );
            }
        }

        $this->languages->multiDelete($request->ids);
        
        foreach($affectedLanguages as $language) {
            // Update users langauge to default after deleted the language they are using
            $this->users->updateUsersLocaleToDefault($language->code);
        }

        event(new LanguagesDeleted($this->languages, $request->ids));

        return $this->handleAjaxRedirectResponse(
            __('Selected language(s) deleted successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Restore the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(Request $request)
    {
        $this->authorize('admin.languages.restore');

        $translations = new Translations;

        $language = $this->languages->withTrashed()->where('id', $request->id);

        $first = $language->first();

        $translations->updateByDefault($first->code);

        $language->restore();
        
        event(new LanguagesRestored($first));

        return $this->successResponse(
            __('Selected language(s) restored successfully.')
        );
    }

    /**
     * Force delete the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function forceDelete(Request $request)
    {
        $this->authorize('admin.languages.force-delete');

        $language = $this->languages->withTrashed()->where('id', $request->id);

        $first = $language->first();

        $language->forceDelete();
        
        event(new LanguagesForceDeleted($first));

        return $this->successResponse(
            __('Selected language(s) force deleted successfully.')
        );
    }

    /**
     * Translate language phrase
     * 
     * @param TranslateLanguagePhraseRequest $request
     * 
     * @return JsonResponse
     */
    public function translateLanguage(TranslateLanguagePhraseRequest $request) 
    {
        $translations = new Translations;

        $language = $this->languages->findOrFail($request->get('id'));

        $translated = GoogleTranslate::trans($request->get('value'), $language->code);

        $translations->put(
            $language->code, 
            $request->get('key'),
            $translated
        );

        return $this->successResponse(__('Language phrase translated successfully.'), [
            'value' => $translated
        ]);
    }

    /**
     * Update language phrase
     * 
     * @param UpdateLanguagePhraseRequest $request
     * 
     * @return JsonResponse
     */
    public function updateLanguage(UpdateLanguagePhraseRequest $request) 
    {
        $translations = new Translations;
        $language = $this->languages->findOrFail($request->get('id'));

        $translations->put(
            $language->code, 
            $request->get('key'), 
            $request->get('value'),
            $request->get('default')
        );

        event(new LanguagesPhraseUpdated(
            $language, 
            $request->get('key')
        ));

        return $this->successResponse(
            __('Language phrase updated successfully.')
        );
    }

    /**
     * Add language phrase
     * 
     * @param AddLanguagePhraseRequest $request
     * 
     * @return JsonResponse
     */
    public function addLanguagePhrase(AddLanguagePhraseRequest $request) 
    {   
        $translations = new Translations;

        foreach($request->get('value') as $code => $phrase) {
            $translations->put($code, $request->get('key'), $phrase);
        }

        event(new LanguagesPhraseCreated($request->get('key')));

        return $this->successResponse(
            __('Language phrase added successfully.')
        );
    }

    /**
     * Sync all langauge
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function syncAllLanguage(Request $request) 
    {
        $this->authorize('admin.languages.sync-all-language');
        
        $translations = new Translations;

        foreach($this->languages->all() as $language) {
            $translations->updateByDefault($language->code);
        }

        event(new LanguagesSync());

        return $this->successResponse(
            __('Language sync successfully.')
        );
    }

    /**
     * Auto translate adding phrase
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function autoTranslateAddingPhrase(Request $request) 
    {
        
        $input = [];
        $input['key'] = $request->get('key');
        $input['value'] = $request->get('value');

        foreach($input['value'] as $code => $value) {
            $input['value'][$code] = GoogleTranslate::trans($input['key'], $code);
        }

        return $this->successResponse(
            __('Translated successfully.'),
            $input
        );
    }


    /**
     * Setting the language path by code
     * 
     * @param string $code
     * 
     * @return string
     */
    protected function setLangPath($code) 
    {
        return base_path() . '/lang/' . $code . '.json';
    }
}
