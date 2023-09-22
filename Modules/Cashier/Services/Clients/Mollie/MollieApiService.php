<?php

namespace Modules\Cashier\Services\Clients\Mollie;

use Modules\Cashier\Services\Clients\ClientApiService;

class MollieApiService extends ClientApiService
{
    public function isValid()
    {
        try {
            $response = $this->service->api()->customers->all(['limit' => 1]);
            if (is_array($response)) {
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
        if ($this->service->config['api_key']) {
            return true;
        }

        return false;
    }
}