<?php

namespace Modules\Subscriptions\Services\Service\Price;

class Price
{
    /**
     * @var PriceFactory
     */
    private $priceFactory;

    /**
     * Constructor
     *
     * @param PriceFactory $priceFactory - (Dependency injection) If not provided, a PriceFactory instance will be constructed.
     */
    public function __construct(PriceFactory $priceFactory = null)
    {
        if (null === $priceFactory) {
            // Create the service factory
            $priceFactory = new PriceFactory();
        }
        $this->priceFactory = $priceFactory;
    }

    /**
     * @param  string $service
     *
     */

    public function provider($provider)
    {
        try {
            return $this->priceFactory->{$provider};
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}