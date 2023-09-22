<?php

namespace Modules\Withdrawals\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Wallet\Repositories\ManualGatewaysRepository;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class WithdrawMethodController extends BaseController
{
    /**
     * @var ManualGatewaysRepository
     */
    protected $manualGateways;

    /**
     * @var AvailableCurrenciesRepository
     */
    protected $currencies;

    public function __construct(
        ManualGatewaysRepository $manualGateways,
        AvailableCurrenciesRepository $currencies
    ) {
        parent::__construct();

        $this->manualGateways = $manualGateways;
        $this->currencies = $currencies;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.withdrawals.methods.index');

        return view('withdrawals::admin.method.index', [
            'pageTitle' => __('Withdrawal Methods')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.withdrawals.methods.create');

        $currencies = $this->currencies->getActive();
        if (setting('allow_wallet_multi_currency') !== 'enable') {
            $currencies = $currencies->where('code', setting('currency'));
        }

        return view('wallet::admin.gateway.create', [
            'backUrl' => route('admin.withdrawals.methods.index'),
            'pageTitle' => __('Create Withdrawal Method'),
            'methodTitle' => __('Manual Withdrawal Methods'),
            'type' => 'withdraw',
            'currencies' => $currencies
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function datatable(Request $request)
    {
        $this->authorize('admin.withdrawals.methods.datatable');

        return DataTables::eloquent(
            $this->manualGateways->getModel()->with(['logo.model'])->where('type', 'withdraw')
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

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.withdrawals.methods.edit');

        $manualGateway = $this->manualGateways->findOrFail($id);

        $currencies = $this->currencies->getActive();
        if (setting('allow_wallet_multi_currency') !== 'enable') {
            $currencies = $currencies->where('code', setting('currency'));
        }

        return view('wallet::admin.gateway.edit', [
            'backUrl' => route('admin.withdrawals.methods.index'),
            'pageTitle' => __('Edit Withdrawal Method'),
            'methodTitle' => __('Manual Withdrawal Methods'),
            'type' => 'withdraw',
            'manualGateway' => $manualGateway,
            'currencies' => $currencies
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
