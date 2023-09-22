<?php

namespace Modules\Carts\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Carts\Repositories\TaxRatesRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class TaxRateDatatableController extends BaseController
{
    /**
     * @var TaxRatesRepository
     */
    protected $taxRates;

    public function __construct(TaxRatesRepository $taxRates)
    {
        parent::__construct();

        $this->taxRates = $taxRates;
    }

    /**
     * Display a listing of the resource.
     * @return array
     */
    public function handle(Request $request)
    {
        $this->authorize('admin.tax-rates.datatable');

        return DataTables::eloquent(
            $this->taxRates->getModel()
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