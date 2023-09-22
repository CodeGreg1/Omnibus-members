<?php

namespace Modules\Subscriptions\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Subscriptions\Services\Invoice as ServicesInvoice;

class InvoiceReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public $payment;

    public $subscription;

    public $owner;

    public $options;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ServicesInvoice $invoice, $payment, $subscription, $owner, $options)
    {
        $this->invoice = $invoice;
        $this->payment = $payment;
        $this->subscription = $subscription;
        $this->owner = $owner;
        $this->options = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('subscriptions::invoice.receipt')
            ->with([
                'pageTitle' => __('Invoice') . '-' . $this->payment->id,
                'invoice' => $this->invoice,
                'subscription' => $this->subscription,
                'user' => $this->owner
            ]);
    }
}