<?php

namespace Modules\Cashier\Services\Clients\Mollie\Service;

use Modules\Cashier\Services\Clients\Mollie\MollieClient;
use Modules\Cashier\Services\Clients\Mollie\ApiResources\All;
use Modules\Cashier\Services\Clients\Mollie\ApiResources\Create;

class SubscriptionService extends MollieClient
{
    use All;

    protected $resourceName = 'subscriptions';

    public function retrieve($customerId, $id)
    {
        $endPoint = "customers/$customerId/subscriptions/$id";

        try {
            $result = $this->request('get', $endPoint);
            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function create($customerId, $options = [])
    {
        $endPoint = 'customers/' . $customerId . '/' . $this->resourceName;

        try {
            $result = $this->request('post', $endPoint, $options);
            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function cancel($subscription)
    {
        $customerId = $subscription->getMeta('customerId');
        $endPoint = 'customers/' . $customerId . '/' . $this->resourceName . '/' . $subscription->ref_profile_id;

        try {
            $result = $this->request('delete', $endPoint);
            $result = json_decode(json_encode($result), FALSE);
            if ($result && isset($result->id)) {
                return $result;
            }
        } catch (\Exception $e) {
            report($e);
            return null;
        }

        return null;
    }

    public function update($subscription, $payload)
    {
        $customerId = $subscription->getMeta('customerId');
        $endPoint = 'customers/' . $customerId . '/' . $this->resourceName . '/' . $subscription->ref_profile_id;

        try {
            $result = $this->request('patch', $endPoint, $payload);
            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}
