<?php

namespace Modules\CategoryTypes\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Modules\CategoryTypes\Events\CategoryTypesCreated;
use Modules\CategoryTypes\Events\CategoryTypesDeleted;
use Modules\CategoryTypes\Events\CategoryTypesUpdated;
use Modules\CategoryTypes\Events\CategoryTypesRestored;
use Modules\CategoryTypes\Events\CategoryTypesForceDeleted;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\CategoryTypes\Repositories\CategoryTypesRepository;
use Modules\CategoryTypes\Http\Requests\AdminStoreCategoryTypeRequest;
use Modules\CategoryTypes\Http\Requests\AdminUpdateCategoryTypeRequest;

class CategoryTypesController extends BaseController
{   
    /**
     * @var CategoryTypesRepository $categoryTypes
     */
    protected $categoryTypes;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/category-types';

    /**
     * @param CategoryTypesRepository $categoryTypes
     * 
     * @return void
     */
    public function __construct(CategoryTypesRepository $categoryTypes) 
    {
        $this->categoryTypes = $categoryTypes;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.category-types.index');

        return view('categorytypes::admin.index', [
            'pageTitle' => __('Category types'),
            'policies' => JsPolicy::get('category-types')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.category-types.create');

        return view('categorytypes::admin.create', [
            'pageTitle' => __('Create new category type')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminStoreCategoryTypeRequest $request
     * @return JsonResponse
     */
    public function store(AdminStoreCategoryTypeRequest $request)
    {
        $model = $this->categoryTypes->create($request->only('type', 'name', 'description'));

        event(new CategoryTypesCreated($model));

        return $this->handleAjaxRedirectResponse(
            __('Category type created successfully.'), 
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
        $this->authorize('admin.category-types.show');
        
        return view('categorytypes::admin.show', [
            'pageTitle' => __('Show category type'),
            'categorytypes' => $this->categoryTypes->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.category-types.edit');

        return view('categorytypes::admin.edit', [
            'pageTitle' => __('Edit category type'),
            'categorytypes' => $this->categoryTypes->findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param AdminUpdateCategoryTypeRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AdminUpdateCategoryTypeRequest $request, $id)
    {
        $model = $this->categoryTypes->findOrFail($id);

        $this->categoryTypes
            ->update($model, 
                $request->only('type', 'name', 'description'));

        event(new CategoryTypesUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Category type updated successfully.'), 
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
        $this->authorize('admin.category-types.delete');

        $model = $this->categoryTypes->findOrFail($request->id);

        $this->categoryTypes->delete($model);
        
        event(new CategoryTypesDeleted($model));
        
        return $this->successResponse(__('Category type deleted successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function multiDestroy(Request $request)
    {
        $this->authorize('admin.category-types.multi-delete');
        
        $this->categoryTypes->multiDelete($request->ids);
        
        event(new CategoryTypesDeleted($this->categoryTypes));

        return $this->successResponse(__('Selected category type(s) deleted successfully.'));
    }

    /**
     * Restore the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(Request $request)
    {
        $this->authorize('admin.category-types.restore');

        $categoryTypes = $this->categoryTypes->withTrashed()->where('id', $request->id);

        $categoryTypes->restore();
        
        event(new CategoryTypesRestored($categoryTypes->first()));

        return $this->successResponse(__('Selected category type(s) restored successfully.'));
    }

    /**
     * Force delete the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function forceDelete(Request $request)
    {
        $this->authorize('admin.category-types.force-delete');

        $categoryTypes = $this->categoryTypes->withTrashed()->where('id', $request->id);

        $first = $categoryTypes->first();

        $categoryTypes->forceDelete();
        
        event(new CategoryTypesForceDeleted($first));

        return $this->successResponse(__('Selected category type(s) force deleted successfully.'));
    }
}
