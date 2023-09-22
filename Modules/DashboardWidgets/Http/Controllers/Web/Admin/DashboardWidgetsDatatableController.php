<?php

namespace Modules\DashboardWidgets\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\DashboardWidgets\Repositories\DashboardWidgetsRepository;

class DashboardWidgetsDatatableController extends BaseController
{   
    /**
     * @var DashboardWidgetsRepository $dashboardWidgets
     */
    protected $dashboardWidgets;

    public function __construct(DashboardWidgetsRepository $dashboardWidgets) 
    {
        $this->dashboardWidgets = $dashboardWidgets;

        parent::__construct();
    }

    /**
     * Datatable
     * @return JsonResponse
     */
    public function index()
    {   
        $this->authorize('admin.dashboard-widgets.datatable');
        
        $query = $this->dashboardWidgets->getModel()->query();

        if(request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

        return DataTables::eloquent($query)
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                } else {
                    $query->orderBy('ordering', 'desc');
                }
            })
            ->toJson();
    }
}
