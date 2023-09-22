<?php

namespace Modules\Menus\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class MenusDatatableController extends BaseController
{
    /**
     * @var MenuRepository
     */
    protected $menus;

    /**
     * @param MenuRepository $menus
     */
    public function __construct(MenuRepository $menus) 
    {
        $this->menus = $menus;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('admin.menus.datatable');

        $query = $this->menus
            ->getModel()
            ->query()
            ->whereNull('parent_id');

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
