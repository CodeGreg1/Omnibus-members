<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\PricingTablesRepository;

class PricingTableDatatableController extends BaseController
{
    /**
     * @var PricingTablesRepository
     */
    protected $pricingTables;

    public function __construct(PricingTablesRepository $pricingTables)
    {
        parent::__construct();

        $this->pricingTables = $pricingTables;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle(Request $request)
    {
        $this->authorize('admin.subscriptions.pricing-tables.datatable');

        return DataTables::eloquent(
            $this->pricingTables->getModel()->with(['items'])
                ->when($request->get('queryValue'), function ($query, $search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('items', function ($query) use ($search) {
                            $query->whereHas('price', function ($query) use ($search) {
                                $query->whereHas('package', function ($query) use ($search) {
                                    $packages = explode(',', $search);
                                    $query->where(function ($query) use ($packages) {
                                        foreach ($packages as $packageName) {
                                            $query->orWhere('name', 'like', "%$packageName%");
                                        }
                                    });
                                });
                            });
                        });
                })
        )
            ->addColumn('packages', function ($row) {
                $packages = [];
                foreach ($row->items as $item) {
                    if (!in_array($item->price->package->name, $packages)) {
                        $packages[] = $item->price->package->name;
                    }
                }
                $string = '';
                $last = array_pop($packages);
                if (count($packages)) {
                    $string = implode(", ", $packages);
                    $string .= ' and ' . $last;
                } else {
                    $string = $last;
                }

                return $string;
            })
            ->addColumn('created', function ($row) {
                return $row->created_at->isoFormat('lll');
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