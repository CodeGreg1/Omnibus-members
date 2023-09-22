<?php

namespace Modules\Cashier\Services\Clients\Stripe\ApiResources;

trait All
{
    public function all()
    {
        $resource = $this->resourceName;

        try {
            $result = $this->client->{$resource}->all()->toArray();

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}