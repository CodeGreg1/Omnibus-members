<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PricingTableItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "pricing_table_id",
        "package_price_id",
        "featured",
        "allow_promo_code",
        "confirm_page_message",
        "order",
        "button_label",
        "button_link"
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['price'];

    /**
     * Get the pricing table that owns the item.
     */
    public function pricingTable()
    {
        return $this->belongsTo(PricingTable::class);
    }

    /**
     * Get the price that owns the item.
     */
    public function price()
    {
        return $this->belongsTo(PackagePrice::class, 'package_price_id');
    }
}