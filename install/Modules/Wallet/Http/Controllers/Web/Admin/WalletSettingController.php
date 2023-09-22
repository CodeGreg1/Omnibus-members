<?php

namespace Modules\Wallet\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Wallet\Http\Requests\UpdateWalletSettings;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class WalletSettingController extends BaseController
{
    /**
     * @var AvailableCurrenciesRepository
     */
    public $currencies;

    public function __construct(AvailableCurrenciesRepository $currencies)
    {
        parent::__construct();

        $this->currencies = $currencies;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.settings.wallet');

        return view('wallet::admin.setting', [
            'pageTitle' => __('Wallet Settings'),
            'currencies' => $this->currencies->getActive()
        ]);
    }

    /**
     * update a resource in storage.
     * @param UpdateWalletSettings $request
     * @return Renderable
     */
    public function update(UpdateWalletSettings $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated());

        return $this->successResponse(__('Wallet settings updated successfully.'));
    }
}
