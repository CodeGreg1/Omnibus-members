<?php

namespace Modules\Cashier\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Modules\Cashier\Facades\Webhook;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Cashier\Events\CashierGatewaySettingsUpdating;
use Modules\Cashier\Http\Requests\UpdateDepositSettingsRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class SettingController extends BaseController
{
    /**
     * @var AvailableCurrenciesRepository
     */
    protected $currencies;

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
        $this->authorize('admin.settings.payment-gateways');

        return view('cashier::settings.payment-gateway', [
            'pageTitle' => __('Payment gateways'),
            'paypal_webhook_events' => Cashier::client('paypal')->service->getConfig('webhook_events'),
            'stripe_webhook_events' => Cashier::client('stripe')->service->getConfig('webhook_events'),
            'currencies' => $this->currencies->getActive(),
            'razorpay_webhook_events' => Cashier::client('razorpay')->service->getConfig('webhook_events')
        ]);
    }

    /**
     * Update general settings for payment gateways
     *
     * @param Request $request
     *
     * @return Json
     */
    public function updateGeneralSettings(Request $request)
    {
        $this->authorize('admin.settings.payment-gateways.general-settings.update');

        $gateway = $request->get('gateway');
        foreach ($request->except(['_token', '_method', 'gateway']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated());

        return $this->successResponse(__('General setting updated successfully.'));
    }

    /**
     * Update settings for payment gateways
     *
     * @param Request $request
     *
     * @return Json
     */
    public function updateApiSettings(Request $request)
    {
        $this->authorize('admin.settings.payment-gateways.api-settings.update');

        CashierGatewaySettingsUpdating::dispatch($request->all());

        foreach ($request->except(['gateway']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated());

        $keyStatus = $request->get('gateway') . '_status';
        if ($request->get($keyStatus) === 'active') {
            try {
                $client = Cashier::client($request->get('gateway'));
                $client->clearCacheCredentials();
                $client->isValid();

                if (!$client->isValid()) {
                    Setting::set($keyStatus, 'archived');
                    Setting::save();
                    return $this->errorResponse(__('Cannot change status to active. Please check keys.'));
                }
            } catch (\Exception $e) {
                report($e);
                Setting::set($keyStatus, 'archived');
                Setting::save();
                return $this->errorResponse(__('Cannot change status to active. Please check keys.'));
            }
        }

        return $this->successResponse(__('Setting updated successfully.'));
    }

    public function updateDepositSettings(UpdateDepositSettingsRequest $request)
    {
        foreach ($request->except(['gateway']) as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated());

        return $this->successResponse(__('Setting updated successfully.'));
    }

    /**
     * Install gateway webhook events to gateway api.
     */
    public function installWebhook($gateway)
    {
        $response = Webhook::provider($gateway)->install();

        if ($response) {
            Setting::set($response->key, $response->value);
            Setting::save();

            event(new SettingsUpdated());

            return $this->successResponse(__('Webhook endpoint installed'), [
                'redirectTo' => route('admin.settings.payment-gateways')
            ]);
        } else {
            return $this->errorResponse(__('Something went wrong. Webhook not installed.'), [
                'redirectTo' => route('admin.settings.payment-gateways')
            ]);
        }
    }

    /**
     * Handle file upload
     * @param Request $request
     * @param string $key
     * @param string $folder
     * @return String
     */
    protected function handleFileUpload(Request $request, $key, $folder)
    {
        if ($request->has($key)) {

            // if setting has a value then delete the file
            if (setting($key)) {
                $src = setting($key);
                if ($src) {
                    $path = Str::replace(url('storage') . '/', '', $src);
                    Storage::disk('public')->delete($path);
                }
            }

            $path = $request->file($key)[0]->store(
                $folder,
                'public'
            );

            $value = url('storage/' . $path);

            return $value;
        }
    }
}
