<?php

namespace Modules\Cashier\Services\Clients\Mollie\ApiResources;

trait All
{
    public function all($options = [])
    {
        try {
            $result = $this->request('get', $this->resourceName, $options);
            if (isset($result['_embedded'])) {
                return json_decode(json_encode($result['_embedded'][$this->resourceName]), FALSE);
            }

            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}