<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Support\Renderable;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Events\PackagePriceCreated;
use Modules\Subscriptions\Events\PackagePriceDeleted;
use Modules\Subscriptions\Events\PackagePriceUpdated;
use Modules\Subscriptions\Repositories\PackagesRepository;
use Modules\Subscriptions\Exceptions\PackagePriceException;
use Modules\Subscriptions\Http\Requests\UpdatePriceRequest;
use Modules\Subscriptions\Repositories\PackagePricesRepository;
use Modules\Subscriptions\Http\Requests\StorePackagePriceRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class PriceController extends BaseController
{
    /**
     * @var PackagePricesRepository
     */
    protected $prices;

    /**
     * @var PackagesRepository
     */
    protected $packages;

    /**
     * @var AvailableCurrenciesRepository
     */
    protected $currencies;

    public function __construct(
        PackagePricesRepository $prices,
        PackagesRepository $packages,
        AvailableCurrenciesRepository $currencies
    ) {
        parent::__construct();

        $this->prices = $prices;
        $this->packages = $packages;
        $this->currencies = $currencies;
    }

    /**
     * Store a newly created resource in storage.
     * @param StorePackagePriceRequest $request
     * @param int $packageId
     * @return Renderable
     */
    public function store(StorePackagePriceRequest $request, $packageId)
    {
        $package = $this->packages->findOrFail($packageId);

        $price = $this->prices->create(array_merge(['package_id' => $package->id], $request->only('package_term_id', 'price', 'currency', 'compare_at_price', 'type', 'trial_days_count')));

        PackagePriceCreated::dispatch($price);

        return $this->successResponse(__(
            'New price created for package :name',
            ['name' => $package->name]
        ), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $packageId
     * @param int $id
     * @return Renderable
     */
    public function show($packageId, $id)
    {
        $price = $this->prices->findFirstWith([
            'id' => $id,
            'package_id' => $packageId
        ], ['term']);

        if (!Gate::allows('admin.subscriptions.packages.prices.show', $price)) {
            abort(403);
        }

        return view('subscriptions::admin.package.price', [
            'pageTitle' => __('Price of ') . $price->package->name,
            'package' => $price->package,
            'price' => $price,
            'currency' => $this->currencies->firstWhere(['code' => Currency::getUserCurrency()])
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdatePriceRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdatePriceRequest $request, $packageId, $priceId)
    {
        $price = $this->prices->whereFirstOrFail(['id' => $priceId, 'package_id' => $packageId]);

        if ($price->hasSubscriptions()) {
            return $this->errorResponse(__('Cannot update price with subscriptions.'));
        }

        DB::transaction(function () use ($price, $request) {
            $oldAttributes = $price->getAttributes();
            $this->prices->update($price, $request->only('package_term_id', 'price', 'currency', 'compare_at_price', 'type', 'trial_days_count', 'enabled'));

            $attributes = $price->fresh()->getNotEqual($oldAttributes, [
                'price', 'currency', 'trial_days_count', 'package_term_id'
            ]);

            if (count($attributes)) {
                PackagePriceUpdated::dispatch($price);
            }
        }, 3);

        return $this->successResponse(__('Package price updated'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $price->package)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($packageId, $priceId)
    {
        $this->authorize('admin.subscriptions.packages.prices.delete');

        $price = $this->prices->whereFirstOrFail([
            'id' => $priceId,
            'package_id' => $packageId
        ]);

        if ($price->subscriptions()->count()) {
            return $this->errorResponse(__('Cannot delete price with subscriptions.'));
        }

        $package = $price->package;

        DB::transaction(function () use ($price) {
            try {
                $this->prices->delete($price);
            } catch (\Exception $e) {
                throw PackagePriceException::cannotBeDelete($e->getMessage());
            }

            try {
                PackagePriceDeleted::dispatch($price);
            } catch (\Exception $e) {
                report($e);
            }
        });

        return $this->successResponse(__('Package price deleted'), [
            'redirectTo' => route('admin.subscriptions.packages.show', $package)
        ]);
    }
}