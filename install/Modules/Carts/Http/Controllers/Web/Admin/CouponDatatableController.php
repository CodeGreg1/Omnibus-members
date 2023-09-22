<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Carts\Repositories\CouponsRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class CouponDatatableController extends BaseController
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
    public function handle(Request $request)
    {
        $this->authorize('admin.coupons.datatable');

        return DataTables::eloquent(
            $this->coupons->getModel()->withCount(['promoCodes'])
                ->when($request->get('type'), function ($query, $type) {
                    $query->where('amount_type', $type);
                })
        )
            ->addColumn('expires', function ($row) {
                return $row->redeem_date_end ? Carbon::parse($row->redeem_date_end)->isoFormat('lll') : '---';
            })
            ->addColumn('terms', function ($row) {
                $string = $row->amount_type === 'percentage' ?
                    real_number($row->amount) :
                    currency_format($row->amount, $row->currency);
                $string .= $row->amount_type === 'percentage' ? '%' : '';
                $string .= ' off';
                if (!$row->billing_duration) {
                    $string .= ' forever';
                } else {
                    if ($row->billing_duration === 1) {
                        $string .= ' once';
                    } else {
                        $string .= ' for ' . $row->billing_duration . ' billing cycle';
                    }
                }

                return $string;
            })
            ->addColumn('times_redeemed', function ($row) {
                return $row->promoCodes()->get()->sum(function ($promoCode) {
                    return $promoCode->subscriptions()->count();
                }, 0);
            })
            ->addColumn('redemptions', function ($row) {
                return $row->redeem_limit_count;
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->toJson();
    }
}