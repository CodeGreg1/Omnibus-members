<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\PackagePricesRepository;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class PackagePriceDatatableController extends BaseController
{
    /**
     * @var PackagePricesRepository
     */
    protected $prices;

    /**
     * @var AvailableCurrenciesRepository
     */
    protected $currencies;

    public function __construct(
        PackagePricesRepository $prices,
        AvailableCurrenciesRepository $currencies
    ) {
        parent::__construct();

        $this->prices = $prices;
        $this->currencies = $currencies;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle($id)
    {
        $this->authorize('admin.subscriptions.packages.prices.datatable');

        return DataTables::eloquent(
            $this->prices->getModel()
                ->withCount([
                    'subscriptions' => function ($query) {
                        $query->whereHas('subscription', function ($q) {
                            $q->whereNull('ends_at')
                                ->orWhere('ends_at', '>=', now());
                        });
                    }
                ])
                ->where('package_id', $id)
        )
            ->addColumn('editable', function ($row) {
                return !$row->hasSubscriptions();
            })
            ->toJson();
    }
}