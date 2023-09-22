<?php

namespace Modules\Services\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Services\Repositories\ServicesRepository;

class ServicesDatatableController extends BaseController
{   
    /**
     * @var ServicesRepository $services
     */
    protected $services;

    /**
     * @param ServicesRepository $services
     * 
     * @return void
     */
    public function __construct(ServicesRepository $services) 
    {
        $this->services = $services;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index()
    {   
        $this->authorize('admin.services.datatable');
        
        $query = $this->services->getModel()->query();

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
