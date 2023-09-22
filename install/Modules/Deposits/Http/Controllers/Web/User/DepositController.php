<?php

namespace Modules\Deposits\Http\Controllers\Web\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Deposits\Repositories\DepositsRepository;
use Modules\Wallet\Repositories\ManualGatewaysRepository;

class DepositController extends BaseController
{
    /**
     * @var DepositsRepository
     */
    public $deposits;

    /**
     * @var ManualGatewaysRepository
     */
    public $manualGateways;

    public function __construct(
        DepositsRepository $deposits,
        ManualGatewaysRepository $manualGateways
    ) {
        parent::__construct();

        $this->deposits = $deposits;
        $this->manualGateways = $manualGateways;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('user.deposits.index');

        return view('deposits::user.index', [
            'pageTitle' => __('Deposit'),
            'automaticGateways' => $this->getAutomaticGateways(),
            'manualGateways' => $this->manualGateways->deposit()->active()->all()
        ]);
    }

    /**
     * Display checkout form.
     * @return Renderable
     */
    public function checkout()
    {
        return view('deposits::user.checkout', [
            'pageTitle' => config('deposits.name')
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function history()
    {
        $this->authorize('user.deposits.histories.index');

        return view('deposits::user.history', [
            'pageTitle' => __('Desposit History')
        ]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('user.deposits.histories.datatable');

        return DataTables::eloquent(
            $this->deposits->getModel()->with(['method'])
                ->where('user_id', $request->user()->id)
        )
            ->addColumn('gateway', function ($row) {
                if ($row->method) {
                    return $row->method->name;
                }

                return Str::title($row->gateway);
            })
            ->addColumn('total', function ($row) {
                return currency_format($row->amount, $row->currency);
            })
            ->addColumn('charge_display', function ($row) {
                return currency_format($row->charge, $row->currency);
            })
            ->addColumn('payable_display', function ($row) {
                return currency_format($row->charge + $row->amount, $row->currency);
            })
            ->addColumn('date', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();;
            })
            ->addColumn('rejected_date', function ($row) {
                return $row->rejected_at ? $row->rejected_at->toUserTimezone()->toUserFormat() : null;
            })
            ->addColumn('gateway_name', function ($row) {
                if ($row->method) {
                    return $row->method->name;
                } else {
                    $gateway = Cashier::getCLient($row->gateway);
                    return $gateway->getConfig('name') ?? $gateway->name;
                }
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

    protected function getAutomaticGateways()
    {
        $array = [];
        $gateways = Cashier::getActiveClients();
        foreach ($gateways as $gateway) {
            $logo = '/themes/codeanddeploy/stisla/assets/img/example-image.jpg';
            if ($gateway->getConfig('logo')) {
                $logo = $gateway->getConfig('logo');
            }
            $array[] = (object) [
                'min_limit' => floatval(setting($gateway->key . '_deposit_min_limit') ?? 0),
                'max_limit' => floatval(setting($gateway->key . '_deposit_max_limit') ?? 0),
                'currency' => setting('currency', config('cashier.currency')),
                'fixed_charge' => floatval(setting($gateway->key . '_deposit_fixed_charge', 0)),
                'percent_charge' => floatval(setting($gateway->key . '_deposit_percent_charge') ?? 0),
                'gateway' => $gateway,
                'name' => $gateway->getConfig('name') ?? $gateway->name,
                'image' => $logo
            ];
        }
        return collect($array);
    }
}
