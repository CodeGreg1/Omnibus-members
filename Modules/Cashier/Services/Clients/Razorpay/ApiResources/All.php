<?php

namespace Modules\Cashier\Services\Clients\Razorpay\ApiResources;

trait All
{
    public function all($options = [])
    {
        try {
            $result = $this->request('get', $this->resourceName, $options);

            if (isset($result['items'])) {
                return json_decode(json_encode($result['items']), FALSE);
            }
        } catch (\Exception $e) {
            report($e);
            return null;
        }

        return null;
    }
}