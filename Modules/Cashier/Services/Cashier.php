<?php

namespace Modules\Cashier\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Cashier\Services\Client;
use Illuminate\Contracts\Cache\Factory as FactoryContract;

/**
 * Class Cashier.
 *
 * used to create and manages payments.
 */
class Cashier
{
    /**
     * Cashier configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * the client the cashier will use
     *
     * @var Client
     */
    protected Client $client;

    /**
     * the available clients
     *
     * @var
     */
    protected $clients = [];

    /**
     * Formatter instance.
     *
     * @var Modules\Cashier\Services\Contracts\CurrencyFormatterInterface
     */
    protected $currency_formatter;

    /**
     * Create a new instance.
     *
     * @param array $config
     * @param FactoryContract $cache
     */
    public function __construct(array $config, FactoryContract $cache)
    {
        $this->config = $config;
        $this->cache = $cache->store($this->config('cache_driver'));
    }

    /**
     * Get instance of cashier client service
     *
     * @param null|string $client
     */
    public function client(string $client = null)
    {
        $service = new ClientService;
        if (!is_null($client)) {
            try {
                return $service->$client;
            } catch (\Exception $e) {
                return null;
            }
        }

        return $service;
    }

    public function getClient($key)
    {
        return Arr::first($this->clients, function ($item) use ($key) {
            return $item->key === $key;
        });
    }

    /**
     * Set clients.
     *
     * @return mixed
     */
    public function clients(array $clients)
    {
        foreach ($clients as $client) {
            if ($client instanceof Client) {
                $client->config = $client->getConfig();
                $this->clients[] = $client;
            }
        }
    }

    public function getClients()
    {
        return $this->clients;
    }

    /**
     * Get the cashier currency from config file
     *
     * @return string
     */
    public function currency()
    {
        return setting('currency', $this->config('currency.default'));
    }

    /**
     * get active clients
     *
     * @return array
     */
    public function getActiveClients()
    {
        return array_filter($this->clients, function ($client) {
            return $client->config && $client->config['status'] === 'active';
        });
    }

    /**
     * Check if there is atleast one active active client
     *
     * @return bool
     */
    public function hasValidCilents()
    {
        return !!count($this->getActiveClients());
    }

    public function getFromViewInstance($view = null, $modes = null, $currency = null)
    {
        $clients = $this->getActiveClients();

        if (is_null($view)) {
            return $clients;
        }

        $clients = array_filter($clients, function ($client) use ($view) {
            return array_key_exists($view, $client->config['view_instance']);
        });

        if ($currency) {
            $currency = Str::upper($currency);
            $clients = array_filter($clients, function ($client) use ($currency) {
                return in_array($currency, $client->config['currencies']);
            });
        }

        if (!$modes) {
            return $clients;
        }

        if (!is_array($modes)) {
            $modes = [$modes];
        }

        return array_values(array_filter($clients, function ($client) use ($view, $modes) {
            return count(array_intersect($modes, $client->config['view_instance'][$view]));
        }));
    }

    /**
     * Get configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function config($key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return Arr::get($this->config, $key, $default);
    }
}