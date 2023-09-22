<?php

namespace Modules\DashboardWidgets\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Modules\Modules\Support\AllModels;
use Spatie\Permission\Models\Permission;
use Modules\Modules\Support\TableColumns;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\DashboardWidgets\Events\DashboardWidgetsCreated;
use Modules\DashboardWidgets\Events\DashboardWidgetsDeleted;
use Modules\DashboardWidgets\Events\DashboardWidgetsUpdated;
use Modules\DashboardWidgets\Events\DashboardWidgetsRestored;
use Modules\DashboardWidgets\Services\Helper\EditWidgetHelper;
use Modules\DashboardWidgets\Services\DashboardWidgetGenerator;
use Modules\DashboardWidgets\Events\DashboardWidgetsForceDeleted;
use Modules\DashboardWidgets\Repositories\DashboardWidgetsRepository;
use Modules\DashboardWidgets\Http\Requests\AdminStoreDashboardWidgetRequest;
use Modules\DashboardWidgets\Http\Requests\AdminUpdateDashboardWidgetRequest;

class DashboardWidgetsController extends BaseController
{   
    /**
     * @var DashboardWidgetsRepository $dashboardWidgets
     */
    protected $dashboardWidgets;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/dashboard-widgets';

    public function __construct(DashboardWidgetsRepository $dashboardWidgets) 
    {
        $this->dashboardWidgets = $dashboardWidgets;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return redirect()->route('dashboard.index');
        
        $this->authorize('admin.dashboard-widgets.index');

        return view('dashboardwidgets::admin.index', [
            'pageTitle' => __('Dashboard widgets'),
            'policies' => JsPolicy::get('dashboard-widgets')
        ]);
    }

    

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.dashboard-widgets.create');

        return view('dashboardwidgets::admin.create', [
            'pageId' => 'dashboard-widgets',
            'pageTitle' => __('Create new dashboard widget'),
            'models' => (new AllModels)->get(),
            'routes' => Permission::where(
                'name', 'LIKE', '%.index'
            )->pluck('name')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminStoreDashboardWidgetRequest $request
     * @return JsonResponse
     */
    public function store(AdminStoreDashboardWidgetRequest $request)
    {
        $model = (new DashboardWidgetGenerator($this->dashboardWidgets, $request->all()))->generate();

        $message = __('Dashboard widget created successfully.');

        if($request->has('id')) {
           $message = __('Dashboard widget updated successfully.'); 
        }

        event(new DashboardWidgetsCreated($model));

        return $this->handleAjaxRedirectResponse(
            $message, 
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
        $this->authorize('admin.dashboard-widgets.edit');

        $loadedRelationships = '';
        $dashboardWidgets = $this->dashboardWidgets->findOrFail($id);
        $attributes = json_decode($dashboardWidgets->attributes, true);
        
        if(isset($attributes['fields'])) {
            $loadedRelationships = (new EditWidgetHelper)->getLoadedRelationshipName($attributes['fields']);
        }

        $tableColumns = (new TableColumns($attributes['model'], true, true))->get();

        return view('dashboardwidgets::admin.edit', [
            'pageId' => 'dashboard-widgets',
            'pageTitle' => __('Edit dashboard widget'),
            'dashboardwidgets' => $dashboardWidgets,
            'attributes' => $attributes,
            'loadedRelationships' => $loadedRelationships,
            'models' => (new AllModels)->get(),
            'tableColumns' => $tableColumns,
            'routes' => Permission::where(
                'name', 'LIKE', '%.index'
            )->pluck('name')  
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param AdminUpdateDashboardWidgetRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AdminUpdateDashboardWidgetRequest $request, $id)
    {
        $model = $this->dashboardWidgets->findOrFail($id);

        $this->dashboardWidgets
            ->update($model, 
                $request->only('name', 'type', 'attributes'));

        event(new DashboardWidgetsUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Dashboard widget updated successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.dashboard-widgets.delete');

        $model = $this->dashboardWidgets->findOrFail($request->id);

        $this->dashboardWidgets->delete($model);

        (new DashboardWidgetGenerator($this->dashboardWidgets, []))->generateCode();
        
        event(new DashboardWidgetsDeleted($model));
        
        return $this->successResponse(__('Dashboard widget deleted successfully.'));
    }

    /**
     * Get table columns
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function tableColumns(Request $request) 
    {
        $this->authorize('admin.dashboard-widgets.index');
        
        if($request->get('table')) {
            return (new TableColumns($request->get('table'), true, true))->get();
        }
        return null;
    }

    /**
     * Reorder dashboard widgets
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function reorder(Request $request) 
    {
        $this->authorize('admin.dashboard-widgets.index');

        $ordering = $request->get('ordering');

        $cntr = 1;

        foreach($ordering as $data) {

            $model = $this->dashboardWidgets->findOrFail($data['id']);

            $this->dashboardWidgets
                ->update($model, [
                    'ordering' => $cntr
                ]);

            $cntr++;
        }

        (new DashboardWidgetGenerator(
            $this->dashboardWidgets, 
            [])
        )->generateCode();

        return $this->successResponse(__('Dashboard widget re-ordered successfully.'));
    }
}
