<?php

namespace Modules\Cashier\Services;

use Modules\Cashier\Facades\Cashier;
use Modules\Cashier\Services\Concerns\InstallWebhook;

class Client
{
    use InstallWebhook;

    /**
     * The key of the client
     *
     * @var string
     */
    public $key;

    /**
     * The name of the client
     *
     * @var string
     */
    public $name;

    /**
     * The config of the client
     *
     * @var array
     */
    public $config;

    /**
     * The service class of the client
     *
     * @var
     */
    public $service_class;

    public function __construct($key, $name, $config, $service_class)
    {
        $this->key = $key;
        $this->name = $name;
        $this->config = $config;
        $this->service_class = $service_class;
    }

    /**
     * Check if client is active
     *
     * @return boolean
     */
    public function active()
    {
        return !!$this->config['enabled'];
    }

    /**
     * Create an api instance for the client
     *
     * @return \Modules\Subscriptions\Services\Clients\ClientApiService
     */
    public function api()
    {
        $service = new ClientService;
        return $service->{$this->key};
    }

    /**
     * the config of client
     *
     * @return array
     */
    public function getConfig($key = null)
    {
        $config = config($this->getConfigPath());
        if ($config && is_array($config)) {
            $c = $config;
        } else if ($config && class_exists($config)) {
            $classConfig = new $config();
            $c = $classConfig->get();
        } else {
            $c = [];
        }

        if ($key) {
            return $c[$key] ?? null;
        }

        return $c;
    }

    /**
     * Get the clients button html.
     *
     * @param string|null $route
     */
    public function getButtonHtml($route = '')
    {
        $buttonConfig = $this->config['button'];
        $href = $route ?? $buttonConfig['href'];
        $html = '<a data-href="' . $href . '" href="' . $href . '" class="' . $buttonConfig['class'] . '" style="width: 100%">';
        $html .= $buttonConfig['content'];
        $html .= '</a>';
        return $html;
    }

    protected function getConfigPath()
    {
        return implode(".", [
            config('cashier.path'),
            'clients',
            $this->key
        ]);
    }
}