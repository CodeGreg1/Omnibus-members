<?php

namespace Modules\Modules\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Modules\Repositories\ModuleRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class ModulesDatatableController extends BaseController
{
    /**
     * @var ModuleRepository $modules
     */
    protected $modules;

    /**
     * @var ModuleRepository $modules
     */
    public function __construct(ModuleRepository $modules) 
    {
        $this->modules = $modules;

        parent::__construct();
    }

    /**
     * Handle modules datatable
     * @return JsonResponse
     */
    public function index()
    {   
        $this->authorize('admin.modules.edit-language-datatable');
        
        $query = $this->modules->getModel()->query();

        if(request()->has('status') && request('status') == 'Core') {
            $query = $query->whereIsCore(1);
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
