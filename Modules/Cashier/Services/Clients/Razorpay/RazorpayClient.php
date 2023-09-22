<?php

namespace Modules\Cashier\Services\Clients\Razorpay;

use Setting;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Http;
use Modules\Cashier\Services\Client;

class RazorpayClient
{
    protected $service;

    protected $client;

    public $webhook_events = [];

    protected $baseUrl = 'https://api.razorpay.com/v1/';

    public function __construct(Client $service)
    {
        $this->service = $service;
        $this->setClient();
    }

    public function setClient()
    {
        // $this->client = Http::withHeaders([
        //     'Authorization' => 'Basic ' . base64_encode($this->service->config['api_id'] . ':' . $this->service->config['api_secret'])
        // ]);

        $this->client = Http::timeout(5)->retry(3, 500)
            ->withBasicAuth(
                $this->service->config['api_id'],
                $this->service->config['api_secret']
            );
    }

    public function getCLient()
    {
        return $this->client;
    }

    public function request($method, $endPoint, $options = [])
    {
        $method = strtolower($method);
        if (!in_array($method, ['post', 'get'])) {
            return null;
        }

        $url = $this->baseUrl . $endPoint;

        return $this->client->{$method}($url, $options)->json();
    }
}
