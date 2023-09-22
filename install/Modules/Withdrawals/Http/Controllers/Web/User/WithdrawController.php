<?php

namespace Modules\Withdrawals\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Wallet\Repositories\ManualGatewaysRepository;
use Modules\Withdrawals\Repositories\WithdrawalsRepository;

class WithdrawController extends BaseController
{
    /**
     * @var WithdrawalsRepository
     */
    public $withdraws;

    /**
     * @var ManualGatewaysRepository
     */
    public $manualGateways;

    public function __construct(
        WithdrawalsRepository $withdraws,
        ManualGatewaysRepository $manualGateways
    ) {
        parent::__construct();

        $this->withdraws = $withdraws;
        $this->manualGateways = $manualGateways;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('user.withdrawals.index');

        return view('withdrawals::user.index', [
            'pageTitle' => __('Withdraw'),
            'hasFunds' => auth()->user()->hasFunds(),
            'methods' => $this->manualGateways->active()->withdraw()->all()
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function history()
    {
        $this->authorize('user.withdrawals.histories.index');

        return view('withdrawals::user.history', [
            'pageTitle' => __('Withdraw History')
        ]);
    }

    public function datatable(Request $request)
    {
        return DataTables::eloquent(
            $this->withdraws->getModel()->with(['method'])
                ->where('user_id', $request->user()->id)
        )
            ->addColumn('method', function ($row) {
                return $row->method->name;
            })
            ->addColumn('total', function ($row) {
                return currency_format($row->amount, $row->currency);
            })
            ->addColumn('charge_display', function ($row) {
                return currency_format($row->charge, $row->currency);
            })
            ->addColumn('receivable', function ($row) {
                return currency_format($row->amount - $row->charge, $row->currency);
            })
            ->addColumn('date', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->addColumn('rejected_date', function ($row) {
                return $row->rejected_at ? $row->rejected_at->toUserTimezone()->toUserFormat() : null;
            })
            ->addColumn('additional_image', function ($row) {
                if ($row->additional_image->count()) {
                    return $row->additional_image->first()->url;
                }

                return null;
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                } else {
                    $query->latest();
                }
            })
            ->toJson();
    }
}
