<?php

namespace Modules\Languages\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Languages\Repositories\LanguagesRepository;

class LanguagesDatatableController extends BaseController
{   
    /**
     * @var LanguagesRepository $languages
     */
    protected $languages;

    /**
     * @param LanguagesRepository $languages
     */
    public function __construct(LanguagesRepository $languages) 
    {
        $this->languages = $languages;

        parent::__construct();
    }

    /**
     * Datatable
     * @return JsonResponse
     */
    public function index()
    {       
        $this->authorize('admin.languages.datatable');
        
        $query = $this->languages->getModel()->query();

        if(request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

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
