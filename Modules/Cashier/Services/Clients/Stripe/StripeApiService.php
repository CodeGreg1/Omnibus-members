<?php

namespace Modules\Cashier\Services\Clients\Stripe;

use Setting;
use Modules\Cashier\Services\Clients\ClientApiService;

class StripeApiService extends ClientApiService
{
    public function getProductKey()
    {
        $products = $this->service->api()->products->all();
        if ($products && $products->data && count($products->data)) {
            return $products->data[0]->id;
        }

        $response = $this->service->api()->products->create([
            'name' => setting('app_name') . ' product'
        ]);

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

        Setting::set($mode . '_stripe_product_key', $key);
        Setting::save();
    }

    public function isValid()
    {
        try {
            $response = $this->service->api()->products->all();
            if ($response && isset($response->data)) {
                return true;
            }
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return false;
    }

    public function hasCredentials()
    {
        if ($this->service->config['key'] && $this->service->config['secret']) {
            return true;
        }

        return false;
    }
}
