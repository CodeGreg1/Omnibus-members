<?php

namespace Modules\Cashier\Services\Clients\Stripe;

use Setting;
use Modules\Cashier\Services\Client;

class StripeClient
{
    protected $service;

    protected $client;

    public $webhook_events = [
        'customer.subscription.created',
        'customer.subscription.deleted',
        'customer.subscription.updated',
        'customer.created',
        'customer.deleted',
        'invoice.created',
        'invoice.paid',
        'invoice.payment_failed',
        'charge.succeeded'
    ];

    public function __construct(Client $service)
    {
        $this->service = $service;
        $this->setClient();
    }

    public function setClient()
    {
        $this->client = new \Stripe\StripeClient($this->service->config['secret']);
    }

    public function getCLient()
    {
        return $this->client;
    }

    protected function setProductKey()
    {
        $mode = setting('cashier_mode', 'sandbox');
        if (!$this->service->getConfig('product_key')) {
            $response = $this->client->products->create([
                'name' => setting('app_name') . ' product'
            ]);

            if ($response) {
                Setting::set($mode . '_stripe_product_key', $response->id);
                Setting::save();
            }
        }
    }
}
