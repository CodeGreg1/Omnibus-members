<?php

namespace Modules\Deposits\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Wallet\Repositories\ManualGatewaysRepository;

class ManualMethodDatatableController extends BaseController
{
    /**
     * @var ManualGatewaysRepository
     */
    public $manualGateways;

    public function __construct(ManualGatewaysRepository $manualGateways)
    {
        parent::__construct();
        $this->manualGateways = $manualGateways;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle(Request $request)
    {
        $this->authorize('admin.deposits.gateways.manuals.datatable');

        return DataTables::eloquent(
            $this->manualGateways->getModel()->with(['logo.model'])->where('type', 'deposit')
                ->when($request->get('status'), function ($query, $status) {
                    $query->when($status === 'active', function ($query) {
                        $query->where('status', 1);
                    })->when($status === 'archived', function ($query) {
                        $query->where('status', 0);
                    });
                })
        )
            ->addColumn('logo', function ($row) {
                if (!$row->logo) {
                    return '/upload/media/default/file-thumb.png';
                }

                return $row->logo->preview_url;
            })
            ->addColumn('min_amount_display', function ($row) {
                return currency_format($row->min_limit, $row->currency);
            })
            ->addColumn('max_amount_display', function ($row) {
                return currency_format($row->max_limit, $row->currency);
            })
            ->addColumn('charge_display', function ($row) {
                return currency_format($row->fixed_charge, $row->currency);
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
