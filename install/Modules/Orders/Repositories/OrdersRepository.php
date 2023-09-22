<?php

namespace Modules\Orders\Repositories;

use Illuminate\Support\Carbon;
use Modules\Orders\Models\Order;
use Modules\Base\Repositories\BaseRepository;
use Modules\Orders\States\Tracking\Cancelled;

class OrdersRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Order::class;

    /**
     * Filter by user id attribute
     *
     * @return self
     */
    public function scopeWhereUserId($userId)
    {
        return $this->addScopeQuery(function ($query) use ($userId) {
            return $query->where('customer_id', $userId);
        });
    }

    /**
     * Cancel order
     *
     * @param Order $order
     * @param string $reason
     *
     * @return Order|null
     */
    public function cancel(Order $order, $reason = '', Carbon $date = null)
    {
        $result = $order->status->transitionTo(Cancelled::class);
        if (!$result) {
            return null;
        }

        $order->cancelled_at = $date ?? now();
        $order->cancel_reason = $reason;
        $order->save();
        return $order->fresh();
    }
}