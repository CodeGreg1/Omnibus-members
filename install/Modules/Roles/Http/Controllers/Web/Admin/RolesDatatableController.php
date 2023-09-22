<?php

namespace Modules\Roles\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Roles\Repositories\RoleRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class RolesDatatableController extends BaseController
{
    /**
     * @var RoleRepository
     */
    protected $roles;
    
    /**
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles) 
    {
        $this->roles = $roles;

        parent::__construct();
    }

    /**
     * Display roles list
     * 
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('admin.roles.datatable');
        
        $query = $this->roles->getModel()->query();

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
