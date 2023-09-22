<?php

namespace Modules\Subscriptions\Exceptions;

use Exception;
use Modules\Subscriptions\Services\Client;

class InvalidClient extends Exception
{
    /**
     * Create a new InvalidClient instance.
     *
     * @param  \Modules\Subscriptions\Services\Client  $client
     * @return static
     */
    public static function undefinedClient(Client $client)
    {
        return new static("The `{$client->name}` is not defined.");
    }
}