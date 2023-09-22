<?php

namespace Modules\Cashier\Services\Clients\Razorpay\Service;

use Modules\Cashier\Services\Clients\Razorpay\RazorpayClient;
use Modules\Cashier\Services\Clients\Razorpay\ApiResources\Create;
use Modules\Cashier\Services\Clients\Razorpay\ApiResources\Retrieve;

class SubscriptionService extends RazorpayClient
{
    use Create, Retrieve;

    protected $resourceName = 'subscriptions';

    public function cancel($subscription)
    {
        $url = $this->resourceName . '/' . $subscription->ref_profile_id . '/cancel';

        try {
            $result = $this->request('post', $url);
            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}