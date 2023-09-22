<?php

namespace Modules\Products\Http\Controllers\Web\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\AvailableCurrencies\Models\AvailableCurrency;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Base\Support\JsPolicy;
use Modules\Carts\Facades\Cart;
use Modules\Carts\Services\ItemConditionCollection;
use Modules\Products\Events\ProductsCreated;
use Modules\Products\Events\ProductsDeleted;
use Modules\Products\Events\ProductsForceDeleted;
use Modules\Products\Events\ProductsRestored;
use Modules\Products\Events\ProductsUpdated;
use Modules\Products\Http\Requests\UserStoreProductRequest;
use Modules\Products\Http\Requests\UserUpdateProductRequest;
use Modules\Products\Repositories\ProductsRepository;

class ProductsController extends BaseController
{
    /**
     * @var ProductsRepository $products
     */
    protected $products;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/user/products';

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
        // $this->authorize('user.products.index');

        return view('products::user.index', [
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
        $this->authorize('user.products.create');

        $currencyIds = AvailableCurrency::pluck('name', 'id');

        return view('products::user.create', [
            'pageTitle' => __('Create new product'),
            'currencyIds' => $currencyIds
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param UserStoreProductRequest $request
     * @return Renderable
     */
    public function store(UserStoreProductRequest $request)
    {
        $model = $this->products->create($request->only('price', 'currency_id_id', 'title'));

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
        // $this->authorize('user.products.show');
        $product = $this->products->findOrFail($id);
        return $product;
        return view('products::user.show', [
            'pageTitle' => __('Show product'),
            'products' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('user.products.edit');

        $currencyIds = AvailableCurrency::pluck('name', 'id');

        return view('products::user.edit', [
            'pageTitle' => __('Edit product'),
            'products' => $this->products->findOrFail($id),
            'currencyIds' => $currencyIds
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UserUpdateProductRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UserUpdateProductRequest $request, $id)
    {
        $model = $this->products->findOrFail($id);

        $this->products
            ->update(
                $model,
                $request->only('price', 'currency_id_id', 'title')
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
        $this->authorize('user.products.delete');

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
        $this->authorize('user.products.multi-delete');

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
        $this->authorize('user.products.restore');

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
        $this->authorize('user.products.force-delete');

        $products = $this->products->withTrashed()->where('id', $request->id);

        $first = $products->first();

        $products->forceDelete();

        event(new ProductsForceDeleted($first));

        return $this->successResponse(__('Selected product(s) force deleted successfully.'));
    }
}