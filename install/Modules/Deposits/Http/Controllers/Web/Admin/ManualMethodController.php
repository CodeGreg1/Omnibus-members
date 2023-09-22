<?php

namespace Modules\Deposits\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Wallet\Repositories\ManualGatewaysRepository;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class ManualMethodController extends BaseController
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
        $this->authorize('admin.deposits.gateways.manuals.index');

        return view('deposits::admin.gateway.index', [
            'pageTitle' => __('Manual deposit methods')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.deposits.gateways.manuals.create');

        $currencies = $this->currencies->getActive();
        if (setting('allow_wallet_multi_currency') !== 'enable') {
            $currencies = $currencies->where('code', setting('currency'));
        }

        return view('wallet::admin.gateway.create', [
            'backUrl' => route('admin.deposits.gateways.manuals.index'),
            'pageTitle' => __('Create Manual Deposit Method'),
            'methodTitle' => __('Manual deposit methods'),
            'type' => 'deposit',
            'currencies' => $currencies
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.deposits.gateways.manuals.edit');

        $manualGateway = $this->manualGateways->findOrFail($id);

        $currencies = $this->currencies->getActive();
        if (setting('allow_wallet_multi_currency') !== 'enable') {
            $currencies = $currencies->where('code', setting('currency'));
        }

        return view('wallet::admin.gateway.edit', [
            'backUrl' => route('admin.deposits.gateways.manuals.index'),
            'pageTitle' => __('Edit Manual Gateway Method'),
            'methodTitle' => __('Manual deposit methods'),
            'type' => 'deposit',
            'manualGateway' => $manualGateway,
            'currencies' => $currencies
        ]);
    }
}
