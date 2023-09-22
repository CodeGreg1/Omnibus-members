<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\PackagesRepository;
use Yajra\DataTables\Facades\DataTables;

class PackageDatatableController extends BaseController
{
    protected $packages;

    public function __construct(PackagesRepository $packages)
    {
        parent::__construct();

        $this->packages = $packages;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle(Request $request)
    {
        $this->authorize('admin.subscriptions.packages.datatable');

        return DataTables::eloquent(
            $this->packages->getModel()
                ->when($request->notIn, function ($query, $packages) {
                    $query->whereNotIn('id', explode(",", $packages));
                })
                ->with(['prices'])
        )
            ->addColumn('description', function ($row) {
                if (count($row->prices) > 1) {
                    return count($row->prices) . ' prices';
                }

                return $row->prices->first()->getPriceDescription();
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