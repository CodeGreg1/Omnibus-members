<?php

namespace Modules\Permissions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Modules\Permissions\Events\PermissionsCreated;
use Modules\Permissions\Events\PermissionsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Permissions\Repositories\PermissionRepository;
use Modules\Permissions\Http\Requests\StorePermissionRequest;
use Modules\Permissions\Http\Requests\UpdatePermissionRequest;

class PermissionsController extends BaseController
{   
    /**
     * @var PermissionRepository
     */
    protected $permissions;

    /**
     * @var $redirectTo
     */
    protected $redirectTo = '/admin/permissions';

    /**
     * @var PermissionRepository $permissions
     */
    public function __construct(PermissionRepository $permissions) 
    {
        $this->permissions = $permissions;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.permissions.index');

        return view('permissions::admin.index', [
            'pageTitle' => config('permissions.name'),
            'policies' => JsPolicy::get('permissions')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.permissions.create');

        return view('permissions::admin.create', [
            'pageTitle' => __('Create new permission')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StorePermissionRequest $request
     * @return JsonResponse
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = $this->permissions->create($request->only('name', 'display_name', 'description'));

        event(new PermissionsCreated($permission));

        return $this->handleAjaxRedirectResponse(
            __('Permission created successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.permissions.edit');

        return view('permissions::admin.edit', [
            'pageTitle' => __('Edit permission'),
            'permission' => $this->permissions->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdatePermissionRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = $this->permissions->find($id);

        $this->permissions->update($permission, $request->only('name', 'display_name', 'description'));

        $permission->refresh();

        event(new PermissionsUpdated($permission));

        return $this->handleAjaxRedirectResponse(
            __('Permission updated successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.permissions.delete');
        
        $this->permissions->multiDelete($request->ids);

        return $this->successResponse(__('Selected permission(s) deleted successfully.'));
    }
}
