<?php

namespace Modules\Orders\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Illuminate\Contracts\Support\Renderable;
use Modules\Orders\Repositories\OrdersRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

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
        return view('orders::user.index', [
            'pageTitle' => __('My orders')
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

        return view('orders::user.show', [
            'pageTitle' => __('Order #:id', ['id' => $order->id]),
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
     * Cancel the specified order.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function cancel(Request $request, $id)
    {
        $order = $this->orders->findOrFail($id);

        $this->authorize('user.orders.cancel', $order);

        $result = $this->orders->cancel($order, $request->reason ?? '');

        if ($result) {
            return $this->successResponse(__('Order cancelled'), [
                'redirectTo' => route('user.orders.show', $order)
            ]);
        }

        return $this->errorResponse(__('Something went wrong on cancelling order'));
    }
}
