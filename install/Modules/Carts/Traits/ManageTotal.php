<?php

namespace Modules\Carts\Traits;

use Modules\Carts\Models\CheckoutItem;
use Modules\AvailableCurrencies\Facades\Currency;

trait ManageTotal
{
    /**
     * Get checkout total amount
     *
     * @param bool $formatted
     * @return string|float
     */
    public function getTotal($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $total = $this->lineItems->sum(function ($item) use ($currency) {
            return $item->getTotalPriceWithTax(false, $currency);
        });

        if ($this->hasDiscount()) {
            $total = $total - $this->getDiscountPrice(false, $currency);
        }

        if ($this->shippingRate) {
            $shippingRate = $this->shippingRate->getPrice(false, $currency);

            $total = $total + $shippingRate;
        }

        if (!$formatted) {
            return $total;
        }

        return currency_format(
            $total,
            $currency
        );
    }

    /**
     * Get checkout subtotal amount
     *
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getSubtotal($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $subtotal = $this->lineItems->sum(function (CheckoutItem $item) use ($currency) {
            return $item->getTotalPrice(false, $currency);
        });

        if (!$formatted) {
            return $subtotal;
        }

        return currency_format(
            $subtotal,
            $currency
        );
    }

    /**
     * Get breakdown of checkout total
     *
     * @param bool $formatted
     * @return array
     */
    public function getConditionBreakdown($formatted = true, $currency = null)
    {
        $data = [];
        $taxRatesArray = [];
        if (!$currency) {
            $firstItem = $this->lineItems->first();
            if ($firstItem) {
                $currency = $firstItem->checkoutable->currency;
            } else {
                $currency = Currency::getUserCurrency();
            }
        }
        $subtotal = $this->getSubtotal(false, $currency);
        $total = $subtotal;

        if ($this->lineItems->count()) {
            $itemTaxRatesArray = $this->lineItems->map(function ($item) use ($currency) {
                return $item->taxRates->map(function ($itemTaxRate) use ($item, $currency) {
                    $itemTotalPrice = $item->getTotalPrice(false, $currency);
                    if ($itemTaxRate->inclusive) {
                        $rate = ($itemTotalPrice / 1.12) * ($itemTaxRate->percentage / 100);
                    } else {
                        $rate = $itemTotalPrice * ($itemTaxRate->percentage / 100);
                    }

                    $rate = number_format($rate, 2, '.', '');

                    return [
                        'title' => $itemTaxRate->title,
                        'rate' => $rate,
                        'inclusive' => $itemTaxRate->inclusive,
                        'percentage' => $itemTaxRate->percentage
                    ];
                });
            })->toArray();

            foreach ($itemTaxRatesArray as $key => $item) {
                foreach ($item as $key => $itemTaxRate) {
                    if (!$itemTaxRate['inclusive']) {
                        $total = $total + $itemTaxRate['rate'];
                    }
                    $title = $itemTaxRate['title'] . ' (' . real_number($itemTaxRate['percentage']) . '%';
                    if ($itemTaxRate['inclusive']) {
                        $title .= ' inclusive';
                    }
                    $title .= ')';
                    if (in_array($title, $taxRatesArray)) {
                        $taxRatesArray[$title] = $taxRatesArray[$title] + $itemTaxRate['rate'];
                    } else {
                        $taxRatesArray[$title] = $itemTaxRate['rate'];
                    }
                }
            }
        }

        if (count($taxRatesArray)) {
            if ($formatted) {
                foreach ($taxRatesArray as $key => $value) {
                    $taxRatesArray[$key] = currency_format($value, $currency);
                }
            }

            $data['taxRates'] = $taxRatesArray;
        }

        if ($this->hasDiscount()) {
            $discount = $this->getDiscountPrice(false, $currency);
            $total = $total - $discount;

            if ($formatted) {
                $discount = currency_format($discount, $currency);
            }

            $data['discount'] = [
                'title' => $this->promoCode->code,
                'description' => $this->promoCode->coupon->getDescription(),
                'amount' => $discount
            ];
        }

        if ($this->shippingRate) {
            $shippingRate = $this->shippingRate->getPrice(false, $currency);

            $total = $total + $shippingRate;

            if (!$shippingRate) {
                $shippingRate = 'Free';
            } else {
                if ($formatted) {
                    $shippingRate = currency_format($shippingRate, $currency);
                }
            }

            $data['shippingRate'] = [
                'title' => $this->shippingRate->title,
                'price' => $shippingRate
            ];
        }

        if ($formatted) {
            $subtotal = currency_format($subtotal, $currency);
        }

        if ($formatted) {
            $total = currency_format($total, $currency);
        }

        $data['subtotal'] = $subtotal;
        $data['total'] = $total;
        $data['currency'] = $currency;

        return $data;
    }

    /**
     * Get breakdown of checkout total
     *
     * @param bool $formatted
     * @param string|null $currency
     * @return string|int|float
     */
    public function getTotalTax($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();
        $totalTax = $this->lineItems->sum(function ($item) use ($currency) {
            $itemTotalPrice = $item->getTotalPrice(false, $currency);
            return $item->taxRates->sum(function ($taxRate) use ($itemTotalPrice, $currency) {
                if ($taxRate->inclusive) {
                    $rate = ($itemTotalPrice / 1.12) * ($taxRate->percentage / 100);
                } else {
                    $rate = $itemTotalPrice * ($taxRate->percentage / 100);
                }

                return (float) number_format($rate, 2, '.', '');
            });
        });

        if (!$formatted) {
            return $totalTax;
        }

        return currency_format(
            $totalTax,
            $currency
        );
    }
}