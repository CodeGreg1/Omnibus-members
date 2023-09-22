<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use Illuminate\Support\Facades\DB;
use Modules\Carts\Events\CouponCreated;
use Modules\Carts\Events\CouponDeleted;
use Modules\Carts\Events\CouponUpdated;
use Illuminate\Contracts\Support\Renderable;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Carts\Repositories\CouponsRepository;
use Modules\Carts\Http\Requests\StoreCouponRequest;
use Modules\Carts\Http\Requests\UpdateCouponRequest;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class CouponController extends BaseController
{
    /**
     * @var CouponsRepository
     */
    protected $coupons;

    /**
     * @var AvailableCurrenciesRepository
     */
    protected $currencies;

    public function __construct(
        CouponsRepository $coupons,
        AvailableCurrenciesRepository $currencies
    ) {
        parent::__construct();

        $this->coupons = $coupons;
        $this->currencies = $currencies;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('carts::admin.coupon.index', [
            'pageTitle' => __('Coupons')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('carts::admin.coupon.create', [
            'pageTitle' => __('New coupon'),
            'currency' => Currency::getUserCurrency(),
            'currencies' => $this->currencies->getActive()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreCouponRequest $request
     * @return Renderable
     */
    public function store(StoreCouponRequest $request)
    {
        $coupon = DB::transaction(function () use ($request) {
            $coupon = $this->coupons->create($request->only('name', 'amount', 'amount_type', 'billing_duration', 'redeem_date_end', 'redeem_limit_count', 'currency'));

            // if ($request->has('plans')) {
            //     $coupon->packages()->attach(explode(",", $request->plans));
            // }

            return $coupon;
        }, 3);

        CouponCreated::dispatch($coupon);

        return $this->successResponse(__('Coupon created'), [
            'redirectTo' => route('admin.coupons.index')
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $coupon = $this->coupons->findOrFail($id);

        $this->authorize('admin.coupons.show', $coupon);

        $timesRedeemed = $coupon->promoCodes()->get()->sum(function ($promoCode) {
            return $promoCode->subscriptions()->count();
        }, 0);

        return view('carts::admin.coupon.show', [
            'pageTitle' => $coupon->name,
            'coupon' => $coupon,
            'packages' => collect([]),
            'timesRedeemed' => $timesRedeemed
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.coupons.edit');

        $coupon = $this->coupons->findOrFail($id);
        $coupon->packages;

        return view('carts::admin.coupon.edit', [
            'pageTitle' => __('Edit coupon'),
            'coupon' => $coupon,
            'currencies' => $this->currencies->getActive()
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateCouponRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateCouponRequest $request, $id)
    {
        $coupon = $this->coupons->findOrFail($id);

        $updatedCoupon = DB::transaction(function () use ($coupon, $request) {
            $this->coupons->update($coupon, $request->only('name', 'amount', 'amount_type', 'billing_duration', 'redeem_date_end', 'redeem_limit_count', 'currency'));

            // $coupon->packages()->detach();

            // if ($request->has('plans')) {
            //     $coupon->packages()->attach(explode(",", $request->plans));
            // }

            return $coupon->fresh();
        }, 3);

        CouponUpdated::dispatch($updatedCoupon);

        return $this->successResponse(__('Coupon updated'), [
            'redirectTo' => route('admin.coupons.edit', $updatedCoupon)
        ]);
    }

    /**
     * Remove a specific resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->authorize('admin.coupons.destroy');

        $coupon = $this->coupons->findOrFail($id);

        if ($coupon->hasSubscriptions()) {
            return $this->errorResponse(__('Coupon has subscriptions. Unable to remove.'));
        }

        $this->coupons->delete($coupon);

        CouponDeleted::dispatch($coupon);

        return $this->successResponse(__('Coupon deleted.'));
    }
}
