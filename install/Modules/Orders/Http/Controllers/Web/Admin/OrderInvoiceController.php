<?php

namespace Modules\Orders\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Payments\Services\Invoice;
use Illuminate\Contracts\Support\Renderable;
use Modules\Orders\Repositories\OrdersRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class OrderInvoiceController extends BaseController
{
    /**
     * @var OrdersRepository
     */
    protected $orders;

    public function __construct(OrdersRepository $orders)
    {
        parent::__construct();

        $this->orders = $orders;
    }

    /**
     * Download order latest invoice
     * @return Renderable
     */
    public function download($id)
    {
        $order = $this->orders->findOrFail($id);
        $this->authorize('admin.orders.download-invoice', $order);

        $latestPayment = $order->payables()->latest()->first();

        $invoice = new Invoice($latestPayment);

        return $invoice->download('invoice', [
            'vendor' => setting('app_name'),
            'owner' => $invoice->getOwner()
        ]);
    }
}