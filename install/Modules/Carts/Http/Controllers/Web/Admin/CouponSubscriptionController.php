<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Carts\Repositories\CouponsRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;

class CouponSubscriptionController extends BaseController
{
    /**
     * @var CouponsRepository
     */
    protected $coupons;

    /**
     * @var SubscriptionsRepository
     */
    protected $subscriptions;

    public function __construct(
        CouponsRepository $coupons,
        SubscriptionsRepository $subscriptions
    ) {
        parent::__construct();

        $this->coupons = $coupons;
        $this->subscriptions = $subscriptions;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function datatable($couponId, Request $request)
    {
        $coupon = $this->coupons->findOrFail($couponId);

        try {
            $this->authorize('admin.coupons.subscriptions');

            return DataTables::eloquent(
                $this->subscriptions->getModel()
                    ->where('subscribable_type', 'App\Models\User')
                    ->has('subscribable')
                    ->whereHas(
                        'discount',
                        function ($query) use ($coupon) {
                            $query->whereHas('promoCode', function ($query) use ($coupon) {
                                $query->where('coupon_id', $coupon->id);
                            });
                        }
                    )
                    ->when($request->get('queryValue'), function ($query, $search) {
                        $query->whereHas('subscribable', function ($query) use ($search) {
                            $query->where('first_name', 'like', '%' . $search . '%')
                                ->orWhere('last_name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        })->orWhereHas('items', function ($query) use ($search) {
                            $query->whereHas('price', function ($query) use ($search) {
                                $query->whereHas('package', function ($query) use ($search) {
                                    $query->where('name', 'like', '%' . $search . '%');
                                });
                            });
                        });
                    })
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