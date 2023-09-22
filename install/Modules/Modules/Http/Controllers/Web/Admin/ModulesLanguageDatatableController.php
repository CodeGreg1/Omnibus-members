<?php

namespace Modules\Modules\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Modules\Repositories\ModuleRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Languages\Repositories\LanguagesRepository;

class ModulesLanguageDatatableController extends BaseController
{  
    /**
     * @var LanguagesRepository $languages
     */
    protected $languages;

    /**
     * @var LanguagesRepository $languages
     */
    public function __construct(LanguagesRepository $languages) 
    {
        $this->languages = $languages;

        parent::__construct();
    }

    /**
     * Handle module languages
     * @return JsonResponse
     */
    public function index()
    {   
        $this->authorize('admin.modules.language-datatable');

        $query = $this->languages->getModel()->query();

        return DataTables::eloquent($query)
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->toJson();
    }
}
