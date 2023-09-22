<?php

namespace Modules\Cashier\Services\Clients\Paypal\Service;

use Modules\Cashier\Services\Clients\Paypal\PaypalClient;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\All;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Create;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Retrieve;

class WebhookService extends PaypalClient
{
    use Create, Retrieve, All;

    protected $resouceName = 'web_hook';

    public function create($payload)
    {
        try {
            $result = $this->client->createWebHook($payload['url'], $payload['events']);

            if (array_key_exists('type', $result) && $result['type'] === 'error') {
                return null;
            }

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function delete($id)
    {
        try {
            $this->client->deleteWebHook($id);
            return true;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function verify($payload)
    {
        try {
            $result = $this->client->verifyWebHook($payload);

            if (array_key_exists('type', $result) && $result['type'] === 'error') {
                return null;
            }

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}