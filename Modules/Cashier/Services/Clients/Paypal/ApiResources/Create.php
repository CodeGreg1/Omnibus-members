<?php

namespace Modules\Cashier\Services\Clients\Paypal\ApiResources;

trait Create
{
    public function create($payload)
    {
        $method = $this->getApiResourceAction('create');

        try {
            $result = $this->client->{$method}($payload, rand(1, 100));
            if (is_array($result) && array_key_exists('type', $result) && $result['type'] === 'error') {
                return null;
            }

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}