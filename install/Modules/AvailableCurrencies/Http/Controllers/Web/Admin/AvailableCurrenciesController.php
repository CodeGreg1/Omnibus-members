<?php

namespace Modules\AvailableCurrencies\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Models\Currency;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Repositories\CurrenciesRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\AvailableCurrencies\Events\AvailableCurrenciesCreated;
use Modules\AvailableCurrencies\Events\AvailableCurrenciesDeleted;
use Modules\AvailableCurrencies\Events\AvailableCurrenciesUpdated;
use Modules\AvailableCurrencies\Events\AvailableCurrenciesRestored;
use Modules\AvailableCurrencies\Events\AvailableCurrenciesForceDeleted;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;
use Modules\AvailableCurrencies\Http\Requests\AdminStoreAvailableCurrencyRequest;
use Modules\AvailableCurrencies\Http\Requests\AdminUpdateAvailableCurrencyRequest;

class AvailableCurrenciesController extends BaseController
{   
    /**
     * @var AvailableCurrenciesRepository $availableCurrencies
     */
    protected $availableCurrencies;

    /**
     * @var CurrenciesRepository $currencies
     */
    protected $currencies;

    /**
     * @var string $defaultCode
     */
    protected $defaultCode = 'USD';

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/currencies';

    public function __construct(AvailableCurrenciesRepository $availableCurrencies, CurrenciesRepository $currencies)
    {
        $this->availableCurrencies = $availableCurrencies;
        $this->currencies = $currencies;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.available-currencies.index');

        return view('availablecurrencies::admin.index', [
            'pageTitle' => __('Currencies'),
            'policies' => JsPolicy::get('available-currencies')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.available-currencies.create');

        $currencies = Currency::pluck('name', 'id');
        return view('availablecurrencies::admin.create', [
            'pageTitle' => __('Create new currency'),
            'currencies' => $currencies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminStoreAvailableCurrencyRequest $request
     * @return Renderable
     */
    public function store(AdminStoreAvailableCurrencyRequest $request)
    {
        $model = $this->availableCurrencies->create($request->only('currency_id', 'name', 'symbol', 'code', 'exchange_rate', 'status', 'format'));

        $this->handleUpdatingDefaultCurrency($model, $request);

        event(new AvailableCurrenciesCreated($model));

        return $this->handleAjaxRedirectResponse(
            __('Currency created successfully.'),
            $this->redirectTo
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.available-currencies.edit');

        $currencies = Currency::pluck('name', 'id');
        return view('availablecurrencies::admin.edit', [
            'pageTitle' => __('Edit currency'),
            'availablecurrencies' => $this->availableCurrencies->findOrFail($id),
            'currencies' => $currencies
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param AdminUpdateAvailableCurrencyRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(AdminUpdateAvailableCurrencyRequest $request, $id)
    {
        $data = $request->only('currency_id', 'name', 'symbol', 'code', 'exchange_rate', 'status', 'format');

        if (!isset($data['status'])) {
            $data['status'] = 0;
        }

        $model = $this->availableCurrencies->findOrFail($id);

        $message = __('Currency updated successfully.');
        if (!$data['status'] && $model->walletBalance()) {
            $message = __('Currency updated successfully. Unable to disable currency. User has wallet for currency.');
            $data['status'] = 1;
        }

        $this->availableCurrencies
            ->update($model, $data);

        $this->handleUpdatingDefaultCurrency($model, $request);

        event(new AvailableCurrenciesUpdated($model));

        return $this->handleAjaxRedirectResponse(
            $message,
            $this->redirectTo
        );
    }

    /**
     * Get currency
     * 
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function getCurrency($id) 
    {
        return $this->successResponse(
            'Success',
            $this->currencies->findOrFail($id)
        );
    }

    /**
     * Handle updating default system currency
     * 
     * @param Model $model
     * @param Request $request
     * 
     * @return void
     */
    protected function handleUpdatingDefaultCurrency($model, Request $request) 
    {
        $currentCurrency = Setting::get(SETTING_CURRENCY_KEY);

        // if current set as default then update the currency settings
        if($request->system_currency) {
            Setting::set(SETTING_CURRENCY_KEY, $request->code);
            Setting::save();

            $model->exchange_rate = 1; //change exchange rate to 1
            $model->status = 1;
            $model->save();
        } else {
            if($currentCurrency == $request->code) {
                Setting::set(SETTING_CURRENCY_KEY, $this->defaultCode);
                Setting::save();

                $dollarCurrency = $this->availableCurrencies->where('code', $this->defaultCode)->first();

                $dollarCurrency->exchange_rate = 1;
                $dollarCurrency->status = 1;
                $dollarCurrency->save();
            }
        }


    }
}
