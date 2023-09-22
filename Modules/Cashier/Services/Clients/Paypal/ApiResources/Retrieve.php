<?php

namespace Modules\Cashier\Services\Clients\Paypal\ApiResources;

trait Retrieve
{
    public function retrieve($id)
    {
        $method = $this->getApiResourceAction('show');

        try {
            $result = $this->client->{$method}($id);
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