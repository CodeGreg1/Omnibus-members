<?php

namespace Modules\Categories\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Categories\Repositories\CategoriesRepository;

class CategoriesDatatableController extends BaseController
{   
    /**
     * @var CategoriesRepository $categories
     */
    protected $categories;

    /**
     * @param CategoriesRepository $categories
     * 
     * @return void
     */
    public function __construct(CategoriesRepository $categories) 
    {
        $this->categories = $categories;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index()
    {   
        $this->authorize('admin.categories.datatable');
        
        $excluded = '->';
        $queryValue = request('queryValue');

        if (strpos($queryValue, $excluded) !== false) {
            $queryValue = preg_replace('/\s+/', ' ', str_replace($excluded, '', $queryValue));

            request()->merge([
                'queryValue' => $queryValue
            ]);

            $request = request()->all();
            $request['search']['value'] = $queryValue;
            
            request()->replace($request);
        }

        $query = $this->categories
            ->getModel()
            ->query()
            ->with([
                'category_type', 
                'parent'
            ]);

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
