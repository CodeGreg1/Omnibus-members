<?php

namespace Modules\Subscriptions\Services\Concerns;

use Exception;
use Modules\Payments\Models\Payment;
use Modules\Subscriptions\Services\Invoice;
use Modules\Subscriptions\Exceptions\InvoiceNotFound;

trait ManagesInvoices
{
    /**
     * Find an invoice by ID.
     *
     * @param  string  $id
     * @return \Modules\Subscriptions\Models\Invoice|null
     */
    public function findInvoice($id)
    {
        $payment = Payment::whereHas('customer', function ($query) {
            $query->whereHas('users', function ($query) {
                $query->where('user_id', $this->id);
            });
        })
            ->with([
                'customer',
                'subscription',
                'subscription.coupon',
                'subscription.packagePrice',
                'subscription.package'
            ])->where('id', $id)->first();

        return $payment ? new Invoice($this, $payment) : null;
    }

    /**
     * Create an invoice download Response.
     *
     * @param  string  $id
     * @param  array  $data
     * @param  string  $filename
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadInvoice($id, array $data = [], $filename = null)
    {
        $invoice = $this->findInvoice($id);

        if (is_null($invoice)) {
            throw new InvoiceNotFound(__('Payment not found with id :id for user :this_id.', [
                'id' => $id,
                'this_id' => $this->id
            ]));
        }

        return $filename ? $invoice->downloadAs('invoice', $filename, $data) : $invoice->download('invoice', $data);
    }

    public function downloadReceipt($id, array $data = [], $filename = null)
    {
        $invoice = $this->findInvoice($id);

        if (is_null($invoice)) {
            throw new InvoiceNotFound(__('Payment not found with id :id for user :this_id.', [
                'id' => $id,
                'this_id' => $this->id
            ]));
        }

        return $filename ? $invoice->downloadAs('receipt', $filename, $data) : $invoice->download('receipt', $data);
    }

    public function sendInvoice($id)
    {
        $invoice = $this->findInvoice($id);

        if (is_null($invoice)) {
            throw new InvoiceNotFound(__('Payment not found with id :id for user :this_id.', [
                'id' => $id,
                'this_id' => $this->id
            ]));
        }

        return $invoice->send([]);
    }

    public function sendInvoiceReceipt($id)
    {
        $invoice = $this->findInvoice($id);

        if (is_null($invoice)) {
            throw new InvoiceNotFound(__('Payment not found with id :id for user :this_id.', [
                'id' => $id,
                'this_id' => $this->id
            ]));
        }

        return $invoice->sendReceipt([]);
    }
}