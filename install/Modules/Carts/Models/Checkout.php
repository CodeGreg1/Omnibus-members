<?php

namespace Modules\Carts\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Modules\Carts\Traits\HasTrial;
use Modules\Carts\Traits\HasDiscount;
use Modules\Carts\Traits\HasShipping;
use Modules\Carts\Traits\ManageTotal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Checkout extends Model
{
    use HasFactory, ManageTotal, HasDiscount, HasShipping, HasTrial;

    protected $fillable = [
        "mode",
        "gateway",
        "cancel_url",
        "success_url",
        "customer_id",
        "promo_code_id",
        "expires_at",
        "shipping_address_id",
        "billing_address_id",
        "shipping_rate_id",
        "allow_promo_code",
        "collect_shipping_address",
        "collect_billing_address",
        "allow_shipping_method",
        "collect_phone_number",
        "confirm_page_message",
        "metadata"
    ];

    /**
     * Disable model autoincrementing
     */
    public $incrementing = false;

    protected $with = ['lineItems', 'customer', 'shippingRate', 'promoCode'];

    /**
     * Model instance boot method
     *
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $id = (string) Str::uuid();
            $item->id = $id;
        });
    }

    /**
     * Get the customer of the checkout
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get all items for the checkout
     */
    public function lineItems()
    {
        return $this->hasMany(CheckoutItem::class, 'checkout_id', 'id');
    }

    /**
     * Get the parent checkouted model.
     */
    public function checkouted()
    {
        return $this->morphTo();
    }

    /**
     * Get total number of items in the checkout
     *
     * @return int
     */
    public function totalItems()
    {
        return count($this->lineItems);
    }

    /**
     * check if checkout has more that one item
     *
     * @return bool
     */
    public function hasMoreItems()
    {
        return !!($this->totalItems() > 1);
    }

    /**
     * Get metadata item
     *
     * @param string key
     * @return mixed
     */
    public function getMetadata($key = null)
    {
        $metadata = json_decode($this->metadata);
        if (is_null($key)) {
            return $metadata;
        }

        if (isset($metadata->{$key})) {
            return $metadata->{$key};
        }

        return null;
    }
}