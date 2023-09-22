<?php

namespace Modules\Cashier\Services\Clients\Mollie;

use Illuminate\Support\Facades\Http;
use Modules\Cashier\Services\Client;

class MollieClient
{
    protected $service;

    protected $client;

    public $webhook_events = [];

    protected $baseUrl = 'https://api.mollie.com/v2/';

    public function __construct(Client $service)
    {
        $this->service = $service;
    }

    public function setClient()
    {
        $this->client = Http::withToken($this->service->config['api_key']);
    }

    public function getCLient()
    {
        return $this->client;
    }

    public function request($method, $endPoint, $options = [])
    {
        $method = strtolower($method);
        if (!in_array($method, ['post', 'get', 'patch', 'delete'])) {
            return null;
        }

        $url = $this->baseUrl . $endPoint;

        return Http::timeout(8)->retry(3, 500)->withToken($this->service->config['api_key'])
            ->{$method}($url, $options)->json();
    }
}
