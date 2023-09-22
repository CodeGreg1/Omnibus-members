<?php

namespace Modules\Subscriptions\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Modules\Cashier\Facades\Cashier;
use Modules\EmailTemplates\Services\Mailer;
use Symfony\Component\HttpFoundation\Response;
use Modules\Subscriptions\Exceptions\InvalidInvoice;
use Modules\Subscriptions\Services\Contracts\InvoiceRenderer;

class Invoice
{
    /**
     * The owner model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $owner;

    /**
     * The payment model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $payment;

    /**
     * The subscription model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $subscription;

    /**
     * The mailer instance.
     *
     * @var \Modules\EmailTemplates\Services\Mailer
     */
    protected $mailer;

    /**
     * The invoice mail name
     *
     * @var string
     */
    protected $invoiceMailTemplateName = 'Subscription Invoice';

    public function __construct($owner, $payment)
    {
        if ($owner->id !== $payment->subscription->user_id) {
            throw InvalidInvoice::invalidOwner($payment, $owner);
        }
        $this->owner = $owner;
        $this->payment = $payment;
        $this->subscription = $payment->subscription;
        $this->mailer = new Mailer;
    }

    /**
     * Get a Carbon instance for the invoicing date.
     *
     * @param  \DateTimeZone|string  $timezone
     * @return \Carbon\Carbon
     */
    public function date($timezone = null)
    {
        $timezone = $timezone ? $timezone : $this->owner->timezone;

        $carbon = Carbon::create($this->payment->date);

        return $timezone ? $carbon->setTimezone($timezone) : $carbon;
    }

    /**
     * Get a Carbon instance for the invoice's due date.
     *
     * @param  \DateTimeZone|string  $timezone
     * @return \Carbon\Carbon|null
     */
    public function dueDate($timezone = null)
    {
        $timezone = $timezone ? $timezone : $this->owner->timezone;

        if ($this->payment->id) {
            $carbon = $this->subscription->ends_at->sub(
                $this->subscription->term->interval_count,
                $this->subscription->term->interval
            );
        }

        return $timezone ? $carbon->setTimezone($timezone) : $carbon;
    }

    /**
     * Format the given amount into a displayable currency.
     *
     * @param  int  $amount
     * @return string
     */
    protected function formatAmount($amount)
    {
        return Cashier::formatAmount($amount, $this->payment->currency->code);
    }

    public function hasDiscount()
    {
        return !!$this->subscription->promoCode;
    }

    /**
     * Calculate the amount for a given discount.
     *
     * @param  \Laravel\Cashier\Discount  $discount
     * @return string|null
     */
    public function discountFor()
    {
        if ($this->hasDiscount()) {
            return $this->formatAmount($this->subscription->promoCode->coupon->amount, $this->invoice->currency);
        }
    }

    /**
     * Get the total of the invoice (before discounts).
     *
     * @return string
     */
    public function subtotal()
    {
        $discount = 0;
        if ($this->hasDiscount()) {
            $discount = $this->subscription->promoCode->coupon->amount;
        }
        return $this->formatAmount($this->payment->amount + $discount);
    }

    /**
     * Get the total amount that was paid (or will be paid).
     *
     * @return string
     */
    public function total()
    {
        return $this->formatAmount($this->payment->amount);
    }

    public function discountAmount()
    {
        if ($this->hasDiscount()) {
            return $this->formatAmount($this->subscription->promoCode->coupon->amount, $this->invoice->currency);
        }
    }

    public function description()
    {
        return $this->total() . ' due ' . $this->dueDate()->formatLocalized('%B %e, %Y');
    }

    public function coverageDescription()
    {
        $description = '';
        $period = $this->subscription->getPeriod();
        if ($period) {
            if ($period['start']->format('Y') !== $period['end']->format('Y')) {
                $description = $period['start']->format('M d, Y');
                $description .= ' - ';
                $description .= $period['end']->format('M d, Y');
            } else {
                $description = $period['start']->format('M d');
                $description .= ' - ';
                $description .= $period['end']->format('M d, Y');
            }
        }

        return $description;
    }

    /**
     * Get the View instance for the invoice.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\View\View
     */
    public function view($view, array $data = [])
    {
        return View::make('subscriptions::invoice.' . $view, array_merge($data, [
            'pageTitle' => __('Invoice') . '-' . $this->payment->id,
            'invoice' => $this,
            'subscription' => $this->subscription,
            'user' => $this->owner
        ]));
    }

    /**
     * Get the View instance for the invoice.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\View\View
     */
    public function show(array $data = [])
    {
        return View::make('subscriptions::invoice.show', array_merge($data, [
            'pageTitle' => __('Invoice') . '-' . $this->payment->id,
            'invoice' => $this,
            'subscription' => $this->subscription,
            'user' => $this->owner
        ]));
    }

    /**
     * Capture the invoice as a PDF and return the raw bytes.
     *
     * @param  array  $data
     * @return string
     */
    public function pdf($type = 'invoice', array $data)
    {
        $options = config('cashier.invoices.options', []);

        if ($paper = config('cashier.paper')) {
            $options['paper'] = $paper;
        }

        $options['view'] = $type;

        return app(InvoiceRenderer::class)->render($this, $data, $options);
    }

    /**
     * Create an invoice download response.
     *
     * @param  array  $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function download($type = 'invoice', array $data)
    {
        $filename = $data['product'] . '_' . $this->date()->month . '_' . $this->date()->year;

        return $this->downloadAs($type, $filename, $data);
    }

    /**
     * Create an invoice download response with a specific filename.
     *
     * @param  string  $filename
     * @param  array  $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAs($type = 'invoice', $filename, array $data)
    {
        return new Response($this->pdf($type, $data), 200, [
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.pdf"',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type' => 'application/pdf',
            'X-Vapor-Base64-Encode' => 'True',
        ]);
    }

    /**
     * Send the invoice to the customer.
     *
     * @param  array  $options
     * @return $this
     */
    public function send(array $options = [])
    {
        $this->mailer->template($this->invoiceMailTemplateName)
            ->to($this->owner->email)
            ->with([
                'invoice_number' => $this->payment->id,
                'date' => $this->date()->formatLocalized('%B %e, %Y'),
                'due_date' => $this->dueDate()->formatLocalized('%B %e, %Y'),
                'biller_name' => $this->subscription->customer->name,
                'biller_email' => $this->subscription->customer->email,
                'invoice_description' => $this->description(),
                'coverage_description' => $this->coverageDescription(),
                'plan_name' => $this->subscription->package->name,
                'quantity' => $this->subscription->quantity,
                'amount' => $this->subscription->amount_display
            ])->send();
    }

    /**
     * Dynamically get values from the onvoice object.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->payment->{$key};
    }
}