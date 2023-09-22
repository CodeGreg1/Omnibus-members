<?php

namespace Modules\Cashier\Services\Clients\Paypal\Service;

use LDAP\Result;
use Modules\Cashier\Services\Clients\Paypal\PaypalClient;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Create;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Retrieve;

class SubscriptionService extends PaypalClient
{
    use Create, Retrieve;

    protected $resouceName = 'subscription';

    public function activate($id)
    {
        try {
            $this->client->activateSubscription($id, 'Approved');

            return true;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function cancel($subscription)
    {
        try {
            $this->client->cancelSubscription($subscription->ref_profile_id, 'Cancel');

            return true;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function revise($id, $payload)
    {
        try {
            $result = $this->client->reviseSubscription($id, $payload);

            if (is_array($result) && isset($result['error'])) {
                return null;
            }

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function transactions($id)
    {
        try {
            $result = $this->client->listSubscriptionTransactions($id);
            if (is_array($result) && array_key_exists('type', $result) && $result['type'] === 'error') {
                return null;
            }

            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}