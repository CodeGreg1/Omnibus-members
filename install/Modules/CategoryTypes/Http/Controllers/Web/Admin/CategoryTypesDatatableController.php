<?php

namespace Modules\CategoryTypes\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\CategoryTypes\Repositories\CategoryTypesRepository;

class CategoryTypesDatatableController extends BaseController
{   
    /**
     * @var CategoryTypesRepository $categoryTypes
     */
    protected $categoryTypes;

    /**
     * @param CategoryTypesRepository $categoryTypes
     * 
     * @return void
     */
    public function __construct(CategoryTypesRepository $categoryTypes) 
    {
        $this->categoryTypes = $categoryTypes;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index()
    {   
        $this->authorize('admin.category-types.datatable');
        
        $query = $this->categoryTypes->getModel()->query();

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
