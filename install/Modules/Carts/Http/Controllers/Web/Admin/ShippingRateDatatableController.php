<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Repositories\ShippingRatesRepository;

class ShippingRateDatatableController extends BaseController
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
     * @return array|mixed
     */
    public function handle(Request $request)
    {
        $this->authorize('admin.shipping-rates.datatable');

        return DataTables::eloquent(
            $this->shippingRates->getModel()
                ->when($request->get('status'), function ($query, $status) {
                    $query->when($status === 'active', function ($query) {
                        $query->where('active', 1);
                    })->when($status === 'archived', function ($query) {
                        $query->where('active', 0);
                    });
                })
        )
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->toJson();
    }
}