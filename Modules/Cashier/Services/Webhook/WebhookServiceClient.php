<?php

namespace Modules\Cashier\Services\Webhook;

use Modules\Cashier\Facades\Cashier;
use Illuminate\Support\Facades\Response;

class WebhookServiceClient
{
    protected $api;

    public function __construct()
    {
        $this->api = Cashier::client($this->serviceName);
    }

    /**
     * Handle successful calls on the controller.
     *
     * @param  array  $parameters
     * @return \Illuminate\Support\Facades\Response
     */
    protected function successMethod($parameters = [])
    {
        return response('Webhook Handled', 200);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array  $parameters
     * @return \Illuminate\Support\Facades\Response
     */
    protected function missingMethod($parameters = [])
    {
        return new Response;
    }

    /**
     * Install webhook events to gateway.
     *
     * @return mixed
     */
    public function install()
    {
    }
}