<?php

namespace Modules\Roles\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Modules\Roles\Support\RoleType;
use Modules\Roles\Events\RolesCreated;
use Modules\Roles\Events\RolesUpdated;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Modules\Roles\Support\SelectedModule;
use Illuminate\Contracts\Support\Renderable;
use Modules\Roles\Support\ExtractModuleName;
use Modules\Roles\Repositories\RoleRepository;
use Modules\Roles\Support\ExcludedPermissions;
use Modules\Roles\Http\Requests\StoreRoleRequest;
use Modules\Roles\Http\Requests\UpdateRoleRequest;
use Modules\Base\Http\Controllers\Web\BaseController;

class RolesController extends BaseController
{   
    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/roles';
    
    /**
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles) 
    {
        $this->roles = $roles;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.roles.index');

        return view('roles::admin.index', [
            'pageTitle' => __('Roles'),
            'policies' => JsPolicy::get('roles')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.roles.create');

        $permissions = Permission::orderBy('name', 'asc')
            ->whereGuardName('web')
            ->get();

        return view('roles::admin.create', [
            'pageTitle' => __('Create new role'),
            'permissions' => $this->handleParsingPermissionValues($permissions),
            'roleTypes' => RoleType::lists()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param StoreRoleRequest $request
     * 
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {
        ini_set('max_execution_time', '150');

        $role = $this->roles->create($request->only('name', 'display_name', 'type', 'description'));

        $role->syncPermissions($request->get('permissions'));

        event(new RolesCreated($role));

        return $this->handleAjaxRedirectResponse(
            __('Role created successfully.'), 
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
        $this->authorize('admin.roles.edit');

        $permissions = Permission::orderBy('name', 'asc')
            ->whereGuardName('web')
            ->get();
            
        $rolePermissions = $this->roles
            ->getPermissionsById($id)
            ->pluck('name')
            ->toArray();

        return view('roles::admin.edit', [
            'pageTitle' => __('Edit role'),
            'role' => $this->roles->find($id),
            'roleTypes' => RoleType::lists(),
            'rolePermissions' => $this->handleParsingRolePermissions($rolePermissions),
            'permissions' => $this->handleParsingPermissionValues($permissions)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateRoleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, $id)
    {   
        ini_set('max_execution_time', '150');
        
        $role = $this->roles->find($id);

        $this->roles->update($role, $request->only('name', 'display_name', 'type', 'description'));

        $role->syncPermissions($request->get('permissions'));

        event(new RolesUpdated($role));

        return $this->handleAjaxRedirectResponse(
            __('Role updated successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Multi delete roles
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.roles.delete');

        $this->roles->multiDelete($request->ids);

        return $this->successResponse(__('Selected role(s) deleted successfully.'));
    }

    /**
     * Handle parsing permission
     * 
     * @param Permission $permissions
     * 
     * @return array
     */
    protected function handleParsingPermissionValues($permissions) 
    {
        $resultPermissions = [];

        $totalModulePermissions = [];
        foreach( $permissions as $permission ) {
            $module = explode('.', $permission->name);
            
            $moduleName = (new ExtractModuleName($module))->get();

            if (! in_array($moduleName, ExcludedPermissions::lists()) ) {
                $totalModulePermissions[$moduleName]['total'][] = 1;
                if(in_array($moduleName, SelectedModule::lists())) {
                    $totalModulePermissions[$moduleName]['selected'][] = 1;
                }

                $resultPermissions[$moduleName]['permissions'][$permission->name]['name'] = $permission->name;
                $resultPermissions[$moduleName]['permissions'][$permission->name]['display_name'] = $permission->display_name;
                $resultPermissions[$moduleName]['permissions'][$permission->name]['description'] = $permission->description;
                $resultPermissions[$moduleName]['permissions'][$permission->name]['guard'] = $permission->web;
                $resultPermissions[$moduleName]['permissions'][$permission->name]['selected'] = (in_array($moduleName, SelectedModule::lists()));

                if(isset($totalModulePermissions[$moduleName]['selected'])) {
                    $resultPermissions[$moduleName]['selected_all'] = $totalModulePermissions[$moduleName]['total'] == $totalModulePermissions[$moduleName]['selected'];
                } else {
                    $resultPermissions[$moduleName]['selected_all'] = 0;
                }
                
            }
            
        }

        return $resultPermissions;
    }

    /**
     * Handle parsing role permissions
     * 
     * @param array $permissions
     * 
     * @return array [
     *   'module1' =>[
     *      'module1.permission1',
     *      'module1.permission2'
     *   ],
     *   'module2' =>[
     *      'module2.permission1',
     *      'module2.permission2'
     *   ]
     * ]
     */
    protected function handleParsingRolePermissions($permissions) 
    {
        $result = [];
        foreach($permissions as $permission) {

            $module = explode('.', $permission);
            
            $moduleName = (new ExtractModuleName($module))->get();

            $result[$moduleName][] = $permission;
        }

        return $result;
    }
}
