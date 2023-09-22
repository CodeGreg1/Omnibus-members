<?php

namespace Modules\Subscriptions\Services\Service\Product;

class Product
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * Constructor
     *
     * @param ProductFactory $productFactory - (Dependency injection) If not provided, a ProductFactory instance will be constructed.
     */
    public function __construct(ProductFactory $productFactory = null)
    {
        if (null === $productFactory) {
            // Create the service factory
            $productFactory = new ProductFactory();
        }
        $this->productFactory = $productFactory;
    }

    /**
     * @param  string $service
     *
     */

    public function provider($provider)
    {
        try {
            return $this->productFactory->{$provider};
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}