<?php

namespace Modules\Cashier\Services\Clients\Paypal\ApiResources;

trait All
{
    public function all()
    {
        $method = $this->getApiResourceAction('list');

        try {
            $result = $this->client->{$method}();
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