<?php

namespace Modules\Cashier\Services\Clients\Paypal;

use Setting;
use Modules\Cashier\Services\Clients\ClientApiService;

class PaypalApiService extends ClientApiService
{
    public function getProductKey()
    {
        $products = $this->service->api()->products->all();
        if ($products && $products->total_items) {
            return $products->products[0]->id;
        }

        $response = $this->service->api()->products->create([
            'name' => setting('app_name') . ' product',
            "description" => setting('app_name') . ' product api reference',
            "type" => "SERVICE",
            "category" => "SOFTWARE"
        ], rand(1, 100));

        if ($response && isset($response->id)) {
            return $response->id;
        }

        return null;
    }

    public function setProductKey($key = null)
    {
        $mode = setting('cashier_mode', 'sandbox');
        if (is_null($key)) {
            $key = $this->getProductKey();
        }

        Setting::set($mode . '_paypal_product_key', $key);
        Setting::save();
    }

    public function isValid()
    {
        try {
            $response = $this->service->api()->products->all();
            if ($response && isset($response->products)) {
                return true;
            }
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return false;
    }

    public function clearCacheCredentials()
    {
        cache()->forget('access_credentials');
    }

    public function hasCredentials()
    {
        $credentials = $this->service->config[$this->service->config['mode']];
        if ($credentials['client_id'] && $credentials['client_secret']) {
            return true;
        }

        return false;
    }
}
