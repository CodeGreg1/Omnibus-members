<?php

namespace Modules\Carts\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Exceptions\ShippingRateException;
use Modules\Carts\Facades\Cart;
use Modules\Carts\Repositories\ShippingRatesRepository;

class CartsController extends BaseController
{
    /**
     * @var ShippingRatesRepository
     */
    protected $shippingRates;

    public function __construct(ShippingRatesRepository $shippingRates)
    {
        parent::__construct();

        $this->shippingRates = $shippingRates;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return view('carts::index', [
            'pageTitle' => __('My cart')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (!$this->shippingRates->active()->count()) {
            throw ShippingRateException::noActiveShippingRates();
        }

        foreach ($request->items as $key => $item) {
            $model = $item['object'];
            $purchasable = (new $model)->find($item['key']);
            if ($purchasable) {
                $purchasable->addToCart($item['quantity'] ?? 1);
            }
        }

        return $this->successResponse(__('Item added to your cart'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (!$this->shippingRates->active()->count()) {
            throw ShippingRateException::noActiveShippingRates();
        }

        Cart::update($id, $request->all());

        return $this->successResponse(__('Cart item updated'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        Cart::remove($request->items);

        return $this->successResponse(__('Item removed from cart.'));
    }

    public function breakdown(Request $request)
    {
        return Cart::breakdown($request->items ?? []);
    }
}