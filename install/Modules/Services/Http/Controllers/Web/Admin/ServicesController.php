<?php

namespace Modules\Services\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Modules\Services\Events\ServicesCreated;
use Modules\Services\Events\ServicesDeleted;
use Modules\Services\Events\ServicesUpdated;
use Modules\Services\Events\ServicesRestored;
use Modules\Services\Events\ServicesForceDeleted;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Services\Repositories\ServicesRepository;
use Modules\Services\Http\Requests\AdminStoreServiceRequest;
use Modules\Services\Http\Requests\AdminUpdateServiceRequest;

class ServicesController extends BaseController
{   
    /**
     * @var ServicesRepository $services
     */
    protected $services;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/services';

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
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.services.index');

        return view('services::admin.index', [
            'pageTitle' => __('Services'),
            'policies' => JsPolicy::get('services')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.services.create');

        return view('services::admin.create', [
            'pageTitle' => __('Create new service')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminStoreServiceRequest $request
     * @return JsonResponse
     */
    public function store(AdminStoreServiceRequest $request)
    {
        $model = $this->services->create($request->only('icon', 'title', 'content', 'visibility'));

        event(new ServicesCreated($model));

        return $this->handleAjaxRedirectResponse(
            __('Service created successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $this->authorize('admin.services.show');
        
        return view('services::admin.show', [
            'pageTitle' => __('Show service'),
            'services' => $this->services->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.services.edit');

        return view('services::admin.edit', [
            'pageTitle' => __('Edit service'),
            'services' => $this->services->findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param AdminUpdateServiceRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AdminUpdateServiceRequest $request, $id)
    {
        $model = $this->services->findOrFail($id);

        $this->services
            ->update($model, 
                $request->only('icon', 'title', 'content', 'visibility'));

        event(new ServicesUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Service updated successfully.'), 
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
        $this->authorize('admin.services.delete');

        $model = $this->services->findOrFail($request->id);

        $this->services->delete($model);
        
        event(new ServicesDeleted($model));
        
        return $this->successResponse(__('Service deleted successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function multiDestroy(Request $request)
    {
        $this->authorize('admin.services.multi-delete');
        
        $this->services->multiDelete($request->ids);
        
        event(new ServicesDeleted($this->services));

        return $this->successResponse(__('Selected service(s) deleted successfully.'));
    }

    /**
     * Restore the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(Request $request)
    {
        $this->authorize('admin.services.restore');

        $services = $this->services->withTrashed()->where('id', $request->id);

        $services->restore();
        
        event(new ServicesRestored($services->first()));

        return $this->successResponse(__('Selected service(s) restored successfully.'));
    }

    /**
     * Force delete the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function forceDelete(Request $request)
    {
        $this->authorize('admin.services.force-delete');

        $services = $this->services->withTrashed()->where('id', $request->id);

        $first = $services->first();

        $services->forceDelete();
        
        event(new ServicesForceDeleted($first));

        return $this->successResponse(__('Selected service(s) force deleted successfully.'));
    }
}
