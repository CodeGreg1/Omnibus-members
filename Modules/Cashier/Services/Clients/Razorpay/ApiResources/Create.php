<?php

namespace Modules\Cashier\Services\Clients\Razorpay\ApiResources;

trait Create
{
    public function create($options = [])
    {
        try {
            $result = $this->request('post', $this->resourceName, $options);
            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}