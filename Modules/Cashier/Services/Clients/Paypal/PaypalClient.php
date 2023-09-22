<?php

namespace Modules\Cashier\Services\Clients\Paypal;

use Setting;
use Illuminate\Support\Str;
use Modules\Cashier\Services\Client;
use Srmklive\PayPal\Services\PayPal;
use Modules\Cashier\Exceptions\AuthenticationException;

class PaypalClient
{
    protected $service;

    protected $client;

    public $webhook_events = [
        'BILLING.SUBSCRIPTION.UPDATED',
        'BILLING.SUBSCRIPTION.EXPIRED',
        'BILLING.SUBSCRIPTION.CANCELLED',
        'BILLING.SUBSCRIPTION.PAYMENT.FAILED',
        'PAYMENT.SALE.PENDING',
        'PAYMENT.SALE.COMPLETED',
        'PAYMENT.SALE.DENIED',
        'INVOICING.INVOICE.CREATED',
        'INVOICING.INVOICE.PAID'
    ];

    public function __construct(Client $service)
    {
        $this->service = $service;
        $this->setClient();
    }

    public function setClient()
    {
        $provider = new PayPal();
        $provider->setApiCredentials($this->service->config);

        if (!cache()->has('paypal.access_credentials')) {
            $credentials = $provider->getAccessToken();

            if (isset($credentials['error'])) {
                throw AuthenticationException::invalidCredentials($this->service->name);
            }

            cache()->put('paypal.access_credentials', $credentials, $credentials['expires_in']);
        }

        $provider->setAccessToken(cache()->get('paypal.access_credentials'));

        $this->client = $provider;
    }

    public function getCLient()
    {
        return $this->client;
    }

    protected function setProductKey()
    {
        $mode = setting('cashier_mode', 'sandbox');
        if (!$this->service->getConfig('product_key')) {
            $response = $this->client->createProduct([
                'name' => setting('app_name') . ' product',
                "description" => setting('app_name') . ' product api reference',
                "type" => "SERVICE",
                "category" => "SOFTWARE"
            ], rand(1, 100));

            if (
                is_array($response)
                && array_key_exists('type', $response)
                && $response['type'] === 'error'
            ) {
                return null;
            }

            if ($response) {
                Setting::set($mode . '_paypal_product_key', $response['id']);
                Setting::save();
            }
        }
    }

    public function getApiResourceAction($action)
    {
        $method = $action . Str::studly($this->resouceName);
        if ($action === 'show') {
            $method .= 'Details';
        }

        if ($action === 'list') {
            $method .= 's';
        }

        return $method;
    }

    public function client()
    {
        return $this->client;
    }
}
