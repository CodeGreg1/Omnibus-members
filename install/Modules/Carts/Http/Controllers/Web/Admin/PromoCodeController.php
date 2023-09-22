<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Modules\Carts\Events\PromoCodeCreated;
use Modules\Carts\Events\PromoCodeDeleted;
use Modules\Carts\Events\PromoCodeUpdated;
use Illuminate\Contracts\Support\Renderable;
use Modules\Carts\Repositories\CouponsRepository;
use Modules\Carts\Repositories\PromoCodesRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Http\Requests\StorePromoCodeRequest;
use Modules\Carts\Http\Requests\UpdatePromoCodeRequest;

class PromoCodeController extends BaseController
{
    /**
     * @var CouponsRepository
     */
    protected $coupon;

    /**
     * @var PromoCodesRepository
     */
    protected $promoCodes;

    public function __construct(
        CouponsRepository $coupon,
        PromoCodesRepository $promoCodes
    ) {
        parent::__construct();

        $this->coupon = $coupon;
        $this->promoCodes = $promoCodes;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($coupon)
    {
        $this->authorize('admin.coupons.promo-codes.index');

        $query = $this->promoCodes->getModel()
            ->withCount('subscriptions as times_redeemed')
            ->where('coupon_id', $coupon);

        return DataTables::eloquent($query)
            ->addColumn('expires', function ($row) {
                return $row->expires_at ? Carbon::createFromTimestamp($row->expires_at)->isoFormat('lll') : '---';
            })
            ->addColumn('redemptions', function ($row) {
                $string = $row->times_redeemed;
                if ($row->max_redemptions) {
                    $string .= '/' . $row->max_redemptions;
                }
                return $string;
            })
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     * @param StorePromoCodeRequest $request
     * @return Renderable
     */
    public function store(StorePromoCodeRequest $request, $couponId)
    {
        $coupon = $this->coupon->find($couponId);
        if ($coupon->redeem_limit_count && ($coupon->redeem_limit_count <= $coupon->times_redeemed)) {
            return $this->errorResponse(__('Coupon already used. To add promo code update coupons limit.'));
        }

        $promoCode = DB::transaction(
            function () use ($request) {
                $promoCode = $this->promoCodes->create($request->only('coupon_id', 'code', 'active', 'expires_at', 'max_redemptions'));

                if ($request->has('users')) {
                    $promoCode->users()->attach(explode(",", $request->users));
                }

                return $promoCode;
            },
            3
        );

        PromoCodeCreated::dispatch($promoCode);

        return $this->successResponse(__('Created promo code'), [
            'redirectTo' => route('admin.coupons.show', $promoCode->coupon)
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $promoCode = $this->promoCodes
            ->getModel()
            ->withCount('subscriptions as times_redeemed')
            ->findOrFail($id);

        $users = $promoCode->users()->with(['roles'])->get();

        return view('carts::admin.coupon.promo-code', [
            'pageTitle' => __('Promo code details'),
            'promoCode' => $promoCode,
            'coupon' => $promoCode->coupon,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePromoCodeRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdatePromoCodeRequest $request, $id)
    {
        $promoCode = $this->promoCodes->findOrFail($id);

        $this->promoCodes->update($promoCode, $request->only('coupon_id', 'code', 'active', 'expires_at', 'max_redemptions'));

        PromoCodeUpdated::dispatch($promoCode->fresh());

        return $this->successResponse(__('Promo code updated'), [
            'redirectTo' => route('admin.coupons.promo-codes.show', $promoCode->fresh())
        ]);
    }

    public function activate(Request $request)
    {
        $this->authorize('admin.coupons.promo-codes.update');

        foreach ($request->get('id') as $id) {
            $this->promoCodes->activate($this->promoCodes->findOrFail($id));
        }

        return $this->successResponse(__('Promo code activated'));
    }

    public function archive(Request $request)
    {
        $this->authorize('admin.coupons.promo-codes.update');

        foreach ($request->get('id') as $id) {
            $this->promoCodes->archive($this->promoCodes->findOrFail($id));
        }

        return $this->successResponse(__('Promo code set to archied.'));
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('admin.coupons.promo-codes.destroy');

        $promoCode = $this->promoCodes->findOrFail($id);

        if ($promoCode->hasSubscriptions()) {
            return $this->errorResponse(__('Promo code has subscriptions. Unable to remove.'));
        }

        $this->promoCodes->delete($promoCode);

        PromoCodeDeleted::dispatch($promoCode);

        return $this->successResponse(__('Promo code deleted.'));
    }
}
