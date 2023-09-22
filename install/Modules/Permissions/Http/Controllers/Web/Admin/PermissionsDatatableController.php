<?php

namespace Modules\Permissions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Permissions\Repositories\PermissionRepository;

class PermissionsDatatableController extends BaseController
{
    /**
     * @var PermissionRepository
     */
    protected $permissions;

    /**
     * @var PermissionRepository $permissions
     */
    public function __construct(PermissionRepository $permissions) 
    {
        $this->permissions = $permissions;

        parent::__construct();
    }

    /**
     * Display permissions list
     * 
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('admin.permissions.datatable');
        
        $query = $this->permissions->getModel()->query();

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
