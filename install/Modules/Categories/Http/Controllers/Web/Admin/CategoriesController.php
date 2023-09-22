<?php

namespace Modules\Categories\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Modules\Categories\Events\CategoriesCreated;
use Modules\Categories\Events\CategoriesDeleted;
use Modules\Categories\Events\CategoriesUpdated;
use Modules\Categories\Events\CategoriesRestored;
use Modules\Categories\Events\CategoriesForceDeleted;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Categories\Repositories\CategoriesRepository;
use Modules\Categories\Http\Requests\AdminStoreCategoryRequest;
use Modules\Categories\Http\Requests\AdminUpdateCategoryRequest;
use Modules\CategoryTypes\Models\CategoryType;

class CategoriesController extends BaseController
{   
    /**
     * @var CategoriesRepository $categories
     */
    protected $categories;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/categories';

    /**
     * @param CategoriesRepository $categories
     * 
     * @return void
     */
    public function __construct(CategoriesRepository $categories) 
    {
        $this->categories = $categories;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.categories.index');

        return view('categories::admin.index', [
            'pageTitle' => __('Categories'),
            'policies' => JsPolicy::get('categories')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.categories.create');

        $categoryTypes = CategoryType::all();
        
        return view('categories::admin.create', [
            'pageTitle' => __('Create new category'),
            'categoryTypes' => $categoryTypes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminStoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(AdminStoreCategoryRequest $request)
    {
        $model = $this->categories->create($request->only('category_type_id', 'parent_id', 'name', 'description', 'color'));

        event(new CategoriesCreated($model));

        return $this->handleAjaxRedirectResponse(
            __('Category created successfully.'), 
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
        $this->authorize('admin.categories.show');
        
        return view('categories::admin.show', [
            'pageTitle' => __('Show category'),
            'categories' => $this->categories->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.categories.edit');

        $categoryTypes = CategoryType::all();
        
        return view('categories::admin.edit', [
            'pageTitle' => __('Edit category'),
            'categories' => $this->categories->findOrFail($id),
            'categoryTypes' => $categoryTypes
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param AdminUpdateCategoryRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AdminUpdateCategoryRequest $request, $id)
    {
        $model = $this->categories->findOrFail($id);

        $this->categories
            ->update($model, 
                $request->only('category_type_id', 'parent_id', 'name', 'description', 'color'));

        event(new CategoriesUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Category updated successfully.'), 
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
        $this->authorize('admin.categories.delete');

        $model = $this->categories->findOrFail($request->id);

        $this->categories->delete($model);
        
        event(new CategoriesDeleted($model));
        
        return $this->successResponse(__('Category deleted successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function multiDestroy(Request $request)
    {
        $this->authorize('admin.categories.multi-delete');
        
        $this->categories->multiDelete($request->ids);
        
        event(new CategoriesDeleted($this->categories));

        return $this->successResponse(__('Selected category(s) deleted successfully.'));
    }

    /**
     * Restore the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(Request $request)
    {
        $this->authorize('admin.categories.restore');

        $categories = $this->categories->withTrashed()->where('id', $request->id);

        $categories->restore();
        
        event(new CategoriesRestored($categories->first()));

        return $this->successResponse(__('Selected category(s) restored successfully.'));
    }

    /**
     * Force delete the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function forceDelete(Request $request)
    {
        $this->authorize('admin.categories.force-delete');

        $categories = $this->categories->withTrashed()->where('id', $request->id);

        $first = $categories->first();

        $categories->forceDelete();
        
        event(new CategoriesForceDeleted($first));

        return $this->successResponse(__('Selected category(s) force deleted successfully.'));
    }
}
