<?php

namespace Modules\Subscriptions\Services\Service\Invoice;

class Invoice
{
    /**
     * @var InvoiceFactory
     */
    private $invoiceFactory;

    /**
     * Constructor
     *
     * @param InvoiceFactory $invoiceFactory - (Dependency injection) If not provided, a InvoiceFactory instance will be constructed.
     */
    public function __construct(InvoiceFactory $invoiceFactory = null)
    {
        if (null === $invoiceFactory) {
            // Create the service factory
            $invoiceFactory = new InvoiceFactory();
        }
        $this->invoiceFactory = $invoiceFactory;
    }

    /**
     * @param  string $service
     *
     * @return \Userdesk\Subscription\Contracts\Service
     */

    public function provider($provider)
    {
        try {
            return $this->invoiceFactory->{$provider};
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}