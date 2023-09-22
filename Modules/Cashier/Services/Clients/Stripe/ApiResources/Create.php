<?php

namespace Modules\Cashier\Services\Clients\Stripe\ApiResources;

trait Create
{
    public function create($payload)
    {
        $resource = $this->resourceName;

        try {
            $result = $this->client->{$resource}->create($payload)->toArray();

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}