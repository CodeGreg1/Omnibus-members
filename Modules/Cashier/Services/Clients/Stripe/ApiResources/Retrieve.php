<?php

namespace Modules\Cashier\Services\Clients\Stripe\ApiResources;

trait Retrieve
{
    public function retrieve($id)
    {
        $resource = $this->resourceName;

        try {
            $result = $this->client->{$resource}->retrieve($id)->toArray();

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}