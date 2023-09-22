<?php

namespace Modules\Products\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Modules\Products\Events\ProductsCreated;
use Modules\Products\Events\ProductsDeleted;
use Modules\Products\Events\ProductsUpdated;
use Modules\Products\Events\ProductsRestored;
use Modules\Products\Events\ProductsForceDeleted;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Products\Repositories\ProductsRepository;
use Modules\Products\Http\Requests\AdminStoreProductRequest;
use Modules\Products\Http\Requests\AdminUpdateProductRequest;
use Modules\AvailableCurrencies\Models\AvailableCurrency;

class ProductsController extends BaseController
{
    /**
     * @var ProductsRepository $products
     */
    protected $products;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/products';

    public function __construct(ProductsRepository $products)
    {
        $this->products = $products;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.products.index');

        return view('products::admin.index', [
            'pageTitle' => __('Products'),
            'policies' => JsPolicy::get('products')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.products.create');

        $currencyIds = AvailableCurrency::pluck('name', 'id');

        return view('products::admin.create', [
            'pageTitle' => __('Create new product'),
            'currencyIds' => $currencyIds
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminStoreProductRequest $request
     * @return Renderable
     */
    public function store(AdminStoreProductRequest $request)
    {
        $model = $this->products->create($request->only('price', 'currency_id', 'title'));

        event(new ProductsCreated($model));

        return $this->handleAjaxRedirectResponse(
            __('Product created successfully.'),
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
        $this->authorize('admin.products.show');

        return view('products::admin.show', [
            'pageTitle' => __('Show product'),
            'products' => $this->products->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.products.edit');

        $currencyIds = AvailableCurrency::pluck('name', 'id');

        return view('products::admin.edit', [
            'pageTitle' => __('Edit product'),
            'products' => $this->products->findOrFail($id),
            'currencyIds' => $currencyIds
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param AdminUpdateProductRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(AdminUpdateProductRequest $request, $id)
    {
        $model = $this->products->findOrFail($id);

        $this->products
            ->update(
                $model,
                $request->only('price', 'currency_id', 'title')
            );

        event(new ProductsUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Product updated successfully.'),
            $this->redirectTo
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.products.delete');

        $model = $this->products->findOrFail($request->id);

        $this->products->delete($model);

        event(new ProductsDeleted($model));

        return $this->successResponse(__('Product deleted successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function multiDestroy(Request $request)
    {
        $this->authorize('admin.products.multi-delete');

        $this->products->multiDelete($request->ids);

        event(new ProductsDeleted($this->products));

        return $this->successResponse(__('Selected product(s) deleted successfully.'));
    }

    /**
     * Restore the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function restore(Request $request)
    {
        $this->authorize('admin.products.restore');

        $products = $this->products->withTrashed()->where('id', $request->id);

        $products->restore();

        event(new ProductsRestored($products->first()));

        return $this->successResponse(__('Selected product(s) restored successfully.'));
    }

    /**
     * Force delete the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function forceDelete(Request $request)
    {
        $this->authorize('admin.products.force-delete');

        $products = $this->products->withTrashed()->where('id', $request->id);

        $first = $products->first();

        $products->forceDelete();

        event(new ProductsForceDeleted($first));

        return $this->successResponse(__('Selected product(s) force deleted successfully.'));
    }
}