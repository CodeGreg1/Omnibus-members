<?php

namespace Modules\Languages\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Support\Language\Translations;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Languages\Repositories\LanguagesRepository;

class LanguagesEditDatatableController extends BaseController
{
    /**
     * @var Filesytem $file
     */
    protected $file;

    /**
     * @var LanguagesRepository $languages
     */
    protected $languages;

    /**
     * @var Translations $translations
     */
    protected $translations;

    /**
     * @var Filesystem $file
     * @var LanguagesRepository $languages
     * @var Translations $translations
     */
    public function __construct(
        Filesystem $file, 
        LanguagesRepository $languages, 
        Translations $translations
    ) 
    {
        $this->file = $file;
        $this->languages = $languages;
        $this->translations = $translations;

        parent::__construct();
    }

    /**
     * Datatable
     * 
     * NOTE: This datatable only support 2 dimensional array
     * 
     * @return JsonResponse
     */
    public function index($id)
    {   
        $this->authorize('admin.languages.edit-datatable');
        
        $language = $this->languages->findOrFail($id);

        if(!request()->has('type')) {
            $langPath = $this->translations->setLanguagePath($language->code);
            $langDir = $this->translations->setDefaultLanguageDirectory($language->code);

            // create language by default with json type
            if(!file_exists($langPath)) {
                $this->translations->createDefaultByCode($language->code, $this->translations::DEFAULT_LANG_JSON_TYPE);
            }

            // create language by default with group type
            if(!is_dir($langDir)) {
                $this->translations->createDefaultByCode($language->code, $this->translations::DEFAULT_LANG_GROUP_TYPE);
            }

            $content = $this->file->get($langPath);

            return $this->translations->datatable($content);
        }
        
        if(request()->has('type')) {
            $content = trans(request('type'), [], $language->code);

            if(is_array($content)) {
                $result = [];
                $cntr = 0;
                foreach($content as $key => $value) {
                    // check for multi value with array
                    if(is_array($value)) {
                        foreach($value as $k2=>$v2) {
                            if(!is_array($v2)) {
                                $result[request('type') . '.' . $key . '|' . $k2] = utf8_encode($v2);
                            }
                        }
                    } else {
                        $result[request('type') . '.' . $key] = utf8_encode($value);
                    }
                }
                
                return $this->translations->datatable(json_encode($result), true);
            }
        }

    }
}
