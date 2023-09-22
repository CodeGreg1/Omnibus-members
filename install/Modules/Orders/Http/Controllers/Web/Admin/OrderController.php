<?php

namespace Modules\Orders\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Cashier\Facades\Cashier;
use Modules\Orders\Repositories\OrdersRepository;

class OrderController extends BaseController
{
    /**
     * @var OrdersRepository
     */
    private $orders;

    public function __construct(OrdersRepository $orders)
    {
        parent::__construct();

        $this->orders = $orders;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('orders::admin.index', [
            'pageTitle' => config('orders.name')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('orders::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $order = $this->orders->with(['owner', 'shippingAddress', 'items.orderable'])->findOrFail($id);
        $paymentProvider = Cashier::getClient($order->gateway);

        return view('orders::admin.show', [
            'pageTitle' => 'Order #' . $order->id,
            'order' => $order,
            'paymentProvider' => $paymentProvider
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('orders::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}