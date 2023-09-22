<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Carts\Repositories\CouponsRepository;
use Modules\Carts\Repositories\PromoCodesRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;

class PromoCodeSubscriptionController extends BaseController
{
    /**
     * @var PromoCodesRepository
     */
    protected $promoCodes;

    /**
     * @var SubscriptionsRepository
     */
    protected $subscriptions;

    public function __construct(
        PromoCodesRepository $promoCodes,
        SubscriptionsRepository $subscriptions
    ) {
        parent::__construct();

        $this->promoCodes = $promoCodes;
        $this->subscriptions = $subscriptions;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function datatable($couponId)
    {
        $promoCode = $this->promoCodes->findOrFail($couponId);

        try {
            $this->authorize('admin.coupons.promo-codes.subscriptions');

            return DataTables::eloquent(
                $this->subscriptions->getModel()
                    ->where('subscribable_type', 'App\Models\User')
                    ->has('subscribable')
                    ->whereHas(
                        'discount',
                        function ($query) use ($promoCode) {
                            $query->where('promo_code_id', $promoCode->id);
                        }
                    )
            )
                ->addColumn('user', function ($row) {
                    if ($row->subscribable->name) {
                        return $row->subscribable->name;
                    } else {
                        return $row->subscribable->email;
                    }
                })
                ->addColumn('created', function ($row) {
                    $format = auth()->user() ? auth()->user()->date_format : 'Y m, d';
                    return $row->created_at->format($format);
                })
                ->addColumn('status', function ($row) {
                    return $row->getStatus();
                })
                ->addColumn('status_display', function ($row) {
                    return $row->getStatusDisplay();
                })
                ->addColumn('package', function ($row) {
                    return $row->getPackageName();
                })
                ->addColumn('price', function ($row) {
                    return $row->getTotal();
                })
                ->addColumn('is_free', function ($row) {
                    return $row->item->price->price ? false : true;
                })
                ->addColumn('term_description', function ($row) {
                    return $row->getTermDescription();
                })
                ->toJson();
        } catch (\Exception $e) {
            report($e);
            dd($e);
            return DataTables::eloquent($coupon->users()->take(0))
                ->toJson();
        }
    }
}