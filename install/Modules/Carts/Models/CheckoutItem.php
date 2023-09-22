<?php

namespace Modules\Carts\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\AvailableCurrencies\Facades\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckoutItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "checkout_id",
        "quantity"
    ];

    protected $with = ['checkoutable', 'taxRates'];

    /**
     * Get the owner of the item
     */
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    /**
     * Get the parent checkoutable model.
     */
    public function checkoutable()
    {
        return $this->morphTo();
    }

    /**
     * The tax rates that belong to the checkout item.
     */
    public function taxRates()
    {
        return $this->belongsToMany(TaxRate::class);
    }

    /**
     * check if item is orderable.
     *
     * @return bool
     */
    public function isOrderable()
    {
        $key = 'orderables';
        if ($this->checkoutable->relationLoaded($key)) {
            return true;
        }

        if (method_exists($this->checkoutable, $key)) {
            return is_a($this->checkoutable->$key(), "Illuminate\Database\Eloquent\Relations\Relation");
        }

        return false;
    }

    /**
     * get the price
     *
     * @param bool $formatted
     * @param string $currency
     * @return mixed|string|int|float
     */
    public function getPrice($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $price = number_format(currency(
            $this->checkoutable->cartPrice(),
            $this->checkoutable->cartCurrency(),
            $currency,
            false
        ), 2, '.', '');

        if (!$formatted) {
            return (float) $price;
        }

        return currency_format($price, $currency);
    }

    /**
     * get the sum of price
     *
     * @param bool $formatted
     * @param string $currency
     * @return mixed|string|int|float
     */
    public function getTotalPrice($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : $this->checkoutable->currency;

        $price = $this->getPrice(false, $currency) * $this->quantity;

        if (!$formatted) {
            return $price;
        }

        return currency_format($price, $currency);
    }

    /**
     * Get total price with tax
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|int|float
     */
    public function getTotalPriceWithTax($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $total = $this->getTotalPrice(false, $currency);

        $taxRates = $this->taxRates->where(function ($taxRate) {
            return !$taxRate->inclusive;
        });

        if ($taxRates->count()) {
            $totalTaxRate = $taxRates->sum(function ($taxRate) use ($total) {
                $rate = $total * ($taxRate->percentage / 100);
                return (float) number_format($rate, 2, '.', '');
            });

            $total = $total + $totalTaxRate;
        }

        if (!$formatted) {
            return $total;
        }

        return currency_format($total, $currency);
    }

    /**
     * Get total tax of checkout item
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|int|float
     */
    public function getTotalTax($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $totalTax = 0;
        if (count($this->taxRates)) {
            $total = $this->getTotalPrice(false, $currency);

            $totalTax = $this->taxRates->sum(function ($taxRate) use ($total) {
                $rate = $total * ($taxRate->percentage / 100);
                return (float) number_format($rate, 2, '.', '');
            });
        }

        if (!$formatted) {
            return $totalTax;
        }

        return currency_format($totalTax, $currency);
    }
}