<?php

namespace Modules\Carts\Services\Clients\Order;

use Modules\Carts\Models\Checkout;
use Modules\Carts\Services\Clients\AbstractClientService;

class Paypal extends AbstractClientService
{
    protected $gateway = 'paypal';

    public function process(Checkout $checkout)
    {
        $total = $checkout->getTotal(false, 'USD');

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($total, '2', '.', '')
                    ]
                ]
            ],
            'application_context' => [
                'user_action' => 'CONTINUE',
                'return_url' => route('user.pay.approval', [$checkout->id]),
                'cancel_url' => $checkout->cancel_url
            ],
        ];

        return $this->client->checkout->create($payload);
    }

    public function store($token)
    {
        $response = $this->client->checkout->approve($token);

        if ($response) {
            $payload = $response->purchase_units[0]->payments->captures[0]->amount;
            $payload['object'] = 'order';
            $payload['id'] = $response->id;
            return $payload;
        }
        return null;
    }

    public function getToken(array $attributes)
    {
        return $attributes['token'];
    }
}