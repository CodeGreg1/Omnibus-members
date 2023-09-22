<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\AvailableCurrencies\Facades\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "order_id",
        "price",
        "quantity",
        "total_tax",
        "total_discounts",
        "title",
        "attributes"
    ];

    /**
     * Model casts
     */
    protected $casts = [
        'attributes' => 'array'
    ];

    protected $with = ['orderable'];


    /**
     * Get the parent orderable model.
     */
    public function orderable()
    {
        return $this->morphTo();
    }

    /**
     * get price of item
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getUnitPrice($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();
        return currency(
            $this->price,
            $this->orderable->currency,
            $currency,
            $formatted
        );
    }

    /**
     * Get items total amount
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getTotal($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $price = $this->getUnitPrice(false, $currency);

        $total = $price * $this->quantity;

        if (!$formatted) {
            return $total;
        }

        return currency_format(
            $total,
            $currency
        );
    }
}