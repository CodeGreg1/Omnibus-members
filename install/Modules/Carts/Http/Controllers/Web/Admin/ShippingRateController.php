<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Carts\Events\ShippingRateCreated;
use Modules\Carts\Events\ShippingRateDeleted;
use Modules\Carts\Events\ShippingRateUpdated;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Repositories\ShippingRatesRepository;
use Modules\Carts\Http\Requests\StoreShippingRateRequest;
use Modules\Carts\Http\Requests\UpdateShippingRateRequest;
use Modules\Carts\Http\Requests\DestroyShippingRateRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class ShippingRateController extends BaseController
{
    /**
     * @var ShippingRatesRepository
     */
    protected $shippingRates;

    /**
     * @var AvailableCurrenciesRepository
     */
    protected $currencies;

    public function __construct(
        ShippingRatesRepository $shippingRates,
        AvailableCurrenciesRepository $currencies
    ) {
        parent::__construct();

        $this->shippingRates = $shippingRates;
        $this->currencies = $currencies;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('carts::admin.shipping-rate.index', [
            'pageTitle' => __('Shipping rates'),
            'currency' => Currency::getUserCurrency(),
            'currencies' => $this->currencies->getActive()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreShippingRateRequest $request
     * @return Renderable
     */
    public function store(StoreShippingRateRequest $request)
    {
        $response = $this->shippingRates->create($request->only('title', 'price', 'active', 'currency'));

        if ($response) {
            ShippingRateCreated::dispatch($response);
            return $this->successResponse(__('Shipping rate created.'));
        }

        return $this->errorResponse(__('Shipping rate not created.'));
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateShippingRateRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateShippingRateRequest $request, $id)
    {
        $shippingRate = $this->shippingRates->findOrFail($id);
        $response = $this->shippingRates->update(
            $shippingRate,
            $request->only('title', 'price', 'active', 'currency')
        );

        if ($response) {
            ShippingRateUpdated::dispatch($shippingRate->fresh());
            return $this->successResponse(__('Shipping rate update.'));
        }

        return $this->errorResponse(__('Shipping rate not updated.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param DestroyShippingRateRequest $request
     * @return Renderable
     */
    public function destroy(DestroyShippingRateRequest $request)
    {
        foreach ($request->rates as $id) {
            $shippingRate = $this->shippingRates->find($id);
            if ($shippingRate) {
                $this->shippingRates->delete($shippingRate);
                ShippingRateDeleted::dispatch($shippingRate);
            }
        }

        return $this->successResponse(__('Shipping rate/s deleted.'));
    }
}