<?php

namespace Modules\Carts\Services\Clients\Order;

class OrderServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'paypal' => Paypal::class,
        'stripe' => Stripe::class
    ];

    /**
     * @param $config
     */
    public function __construct()
    {
        $this->services = [];
    }

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }

    /**
     * @param string $name
     *
     * @return null
     */
    public function __get($name)
    {
        $serviceClass = $this->getServiceClass($name);

        if (null !== $serviceClass) {
            if (!\array_key_exists($name, $this->services)) {
                $this->services[$name] = new $serviceClass();
            }

            return $this->services[$name];
        }

        \trigger_error('Undefined property: ' . static::class . '::$' . $name);

        return null;
    }
}