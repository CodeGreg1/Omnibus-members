<?php

namespace Modules\Cashier\Services\Clients\Stripe\ApiResources;

trait Delete
{
    public function delete($id)
    {
        $resource = $this->resourceName;

        try {
            $result = $this->client->{$resource}->update($id, ['active' => false])->toArray();
            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}