<?php

namespace Modules\Orders\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Orders\Http\Requests\HandleOrderTrackingStateRequest;
use Modules\Orders\Repositories\OrdersRepository;
use Modules\Orders\States\Tracking\TrackingState;

class HandleOrderTrackingStateController extends BaseController
{
    /**
     * @var OrdersRepository
     */
    public $orders;

    public function __construct(OrdersRepository $orders)
    {
        parent::__construct();

        $this->orders = $orders;
    }

    /**
     * Update the tracking status of specified resource in storage.
     * @param HandleOrderTrackingStateRequest $request
     * @param int $id
     * @return Renderable
     */
    public function handle(HandleOrderTrackingStateRequest $request, $id)
    {
        $order = $this->orders->findOrFail($id);
        $this->authorize('user.orders.update-tracking-state', $order);

        $state = TrackingState::getStateMapping()->get($request->get('status'));

        $order->status->transitionTo($state);

        return $this->successResponse(__('Order tracking status changed'));
    }
}