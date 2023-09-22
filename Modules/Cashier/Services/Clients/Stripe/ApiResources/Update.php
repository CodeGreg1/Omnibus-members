<?php

namespace Modules\Cashier\Services\Clients\Stripe\ApiResources;

trait Update
{
    public function update($id, $payload)
    {
        $resource = $this->resourceName;

        try {
            $result = $this->client->{$resource}->update($id, $payload)->toArray();

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}