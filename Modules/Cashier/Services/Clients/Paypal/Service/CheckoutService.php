<?php

namespace Modules\Cashier\Services\Clients\Paypal\Service;

use Modules\Cashier\Services\Clients\Paypal\PaypalClient;
use Modules\Cashier\Services\Clients\Paypal\ApiResources\Retrieve;

class CheckoutService extends PaypalClient
{
    use Retrieve;

    protected $resouceName = 'order';

    public function create($payload)
    {
        try {
            $approval = $this->client->createOrder($payload);

            if ($approval && isset($approval['status']) && $approval['status'] === 'CREATED') {
                $url = collect($approval['links'])->first(function ($link) {
                    return $link['rel'] === 'approve';
                });

                if ($url) {
                    return json_decode(
                        json_encode([
                            'url' => $url['href']
                        ]),
                        FALSE
                    );
                }
            }
        } catch (\Exception $e) {
            report($e);
            return null;
        }

        return null;
    }

    public function approve($id)
    {
        try {
            $response = $this->client->capturePaymentOrder($id);

            if ($response && isset($response['status']) && $response['status'] === 'COMPLETED') {
                return json_decode(json_encode($response), FALSE);
            }
            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}