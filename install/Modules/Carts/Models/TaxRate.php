<?php

namespace Modules\Carts\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Cashier\Traits\CashierModeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxRate extends Model
{
    use HasFactory, CashierModeScope;

    protected $fillable = [
        "title",
        "description",
        "percentage",
        "inclusive",
        "tax_type",
        "active",
        "live"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'percentage' => 'float'
    ];

    /**
     * Scope a query to only include active tax rates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

    /**
     * The checkout item that belong to the taxt.
     */
    public function checkoutItems()
    {
        return $this->belongsToMany(CheckoutItem::class);
    }
}