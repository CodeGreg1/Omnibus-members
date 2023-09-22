<?php

namespace Modules\Frontend\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Frontend\Repositories\FrontendsRepository;

class FrontendDatatableController extends BaseController
{   
    /**
     * @var FrontendsRepository $frontend
     */
    protected $frontend;

    /**
     * @param FrontendsRepository $frontend
     * 
     * @return void
     */
    public function __construct(FrontendsRepository $frontend) 
    {
        $this->frontend = $frontend;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index()
    {   
        $this->authorize('admin.frontends.datatable');
        
        $query = $this->frontend->getModel()->query();

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
