<?php

namespace Modules\Orders\Models;

use App\Models\User;
use Modules\Base\Models\Address;
use Spatie\ModelStates\HasStates;
use Modules\Payments\Traits\Payable;
use Modules\Orders\Traits\ManageTotal;
use Illuminate\Database\Eloquent\Model;
use Modules\Cashier\Traits\CashierModeScope;
use Modules\Payments\Contracts\PayableInterface;
use Modules\Orders\States\Tracking\TrackingState;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model implements PayableInterface
{
    use HasFactory, HasStates, Payable, ManageTotal, CashierModeScope;

    protected $fillable = [
        "customer_id",
        "cart_token",
        "currency",
        "gateway",
        "total_discounts",
        "shipping_amount",
        "total_tax",
        "subtotal_price",
        "total_price",
        "cancelled_at",
        "cancel_reason",
        "paid",
        "billing_address_id",
        "shipping_address_id",
        "status",
        "note",
        "phone",
        "live"
    ];

    protected $casts = [
        'status' => TrackingState::class,
        'total_discounts' => 'float',
        'shipping_amount' => 'float',
        'total_tax' => 'float',
        'subtotal_price' => 'float',
        'total_price' => 'float'
    ];

    /**
     * get the owner of the order
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * the items of the order
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * the shipping address of the order
     */
    public function shippingAddress()
    {
        return $this->hasOne(Address::class, 'id', 'shipping_address_id');
    }

    public function hasDiscount()
    {
        return !!$this->total_discounts;
    }

    public function invoiceItems()
    {
        return $this->items->map(function ($item) {
            return (object) [
                'title' => $item->title,
                'quantity' => real_number($item->quantity),
                'price' => $item->getUnitPrice(),
                'total' => $item->getTotal(),
                'item' => $item
            ];
        });
    }

    /**
     * Get path for payable
     *
     * @return string
     */
    public function payablePath()
    {
        return route('user.orders.show', $this);
    }

    /**
     * Get invoice attributes for email
     *
     * @return array
     */
    public function invoiceEmailAttributes()
    {
        return [];
    }
}