<?php

namespace Modules\Subscriptions\Services\Service\Invoice\Clients;

use Illuminate\Support\Carbon;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\Subscription;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Subscriptions\Services\Service\Invoice\InvoiceServiceClient;

class Paypal extends InvoiceServiceClient
{
    protected $gateway = 'paypal';

    public function upcoming(Subscription $subscription, PackagePrice $price)
    {
        $data = [];
        $currency = $subscription->item->price->currency;
        $data['currency'] = $currency;
        $newItemPrice = $price->getUnitPrice(false, $currency);
        $currentPrice = $subscription->item->getUnitPrice(
            false,
            $currency
        );

        if ($subscription->onTrial()) {
            $amountDue = $currentPrice;
        } else {
            $now = now()->format('d M, Y');

            $currentPriceUnusedTime = $this->getProratedAmount(
                $currentPrice,
                $subscription->starts_at,
                $subscription->item->price->term->interval,
                $subscription->item->price->term->interval_count
            );

            $newPriceUnusedTime = $this->getProratedAmount(
                $newItemPrice,
                $subscription->starts_at,
                $price->term->interval,
                $price->term->interval_count
            );

            $data['lines'] = [
                [
                    'description' => __('Remaining time on :package after :date', [
                        'package' => $price->package->name,
                        'date' => $now
                    ]),
                    'amount' => $newPriceUnusedTime,
                    'amount_display' => currency(
                        $newPriceUnusedTime,
                        $price->currency,
                        Currency::getUserCurrency()
                    )
                ],
                [
                    'description' => __('Unused time on :package after :date', [
                        'package' => $subscription->item->price->package->name,
                        'date' => $now
                    ]),
                    'amount' => $currentPriceUnusedTime,
                    'amount_display' => '-' . currency(
                        $currentPriceUnusedTime,
                        $currency,
                        Currency::getUserCurrency()
                    )
                ]
            ];

            if ($newPriceUnusedTime >= $currentPriceUnusedTime) {
                $amountDue = $newPriceUnusedTime - $currentPriceUnusedTime;
                $data['next_bill_date'] = now()->add(
                    $price->term->interval,
                    $price->term->interval_count
                );
            } else {
                $amountDue = 0;
                $credit = $currentPriceUnusedTime - $newPriceUnusedTime;
                if ($credit > $newItemPrice) {
                    $totalCreditCyleCoverage = $credit / $newItemPrice;
                    $totalCreditCyleCoverage = intval($totalCreditCyleCoverage);
                    $remaingCoveredAmount = round(($credit - ($newItemPrice * $totalCreditCyleCoverage)), 2);
                    if ($currentPriceUnusedTime === $credit + $newItemPrice) {
                        $remaingCoveredAmount = 0;
                    } else {
                        $remaingCoveredAmount = $remaingCoveredAmount < 0.1
                            ? 0.1
                            : $remaingCoveredAmount;
                    }

                    $creditLastBill = currency(
                        $remaingCoveredAmount,
                        $currency,
                        Currency::getUserCurrency()
                    );
                    if ($totalCreditCyleCoverage - 1) {
                        $data['credit_bill_desc'] = __('If not cancelled, remaining balance covers :count billing cycles and :amount', [
                            'count' => $totalCreditCyleCoverage - 1,
                            'amount' => $creditLastBill
                        ]);
                    }
                    $data['credit_bill_coverage'] = $totalCreditCyleCoverage;
                    $data['credit_last_bill'] = $remaingCoveredAmount;
                    $data['next_bill_date'] = now()->add(
                        $price->term->interval,
                        $totalCreditCyleCoverage + 1
                    );
                    $data['next_bill_desc'] = __(':app_name will try to invoice you on :date for :amount', [
                        'app_name' => setting('app_name'),
                        'date' => $data['next_bill_date']->format('d M, Y'),
                        'amount' => $creditLastBill
                    ]);
                } else {
                    $remaingCoveredAmount = $newItemPrice - $credit;
                    $remaingCoveredAmount = $remaingCoveredAmount < 0.1
                        ? 0.1
                        : $remaingCoveredAmount;
                    $creditLastBill = currency(
                        $remaingCoveredAmount,
                        $currency,
                        Currency::getUserCurrency()
                    );
                    $data['credit_bill_coverage'] = 0;
                    $data['credit_last_bill'] = $remaingCoveredAmount;
                    $data['next_bill_date'] = now()->add(
                        $price->term->interval,
                        $price->term->interval_count
                    );
                    $data['next_bill_desc'] = __(':app_name will try to invoice you on :date for :amount', [
                        'app_name' => setting('app_name'),
                        'date' => $data['next_bill_date']->format('d M, Y'),
                        'amount' => $creditLastBill
                    ]);
                }

                $data['credit'] = $credit;
                $data['credit_display'] = currency(
                    $credit,
                    $currency,
                    Currency::getUserCurrency()
                );
            }
        }


        $data['amount_due'] = $amountDue;
        $data['amount_due_display'] = currency(
            $amountDue,
            $currency,
            Currency::getUserCurrency()
        );

        return $data;
    }

    protected function getProratedAmount($price, $startDate, $interval, $interval_count)
    {
        $usedTime = (now()->timestamp - $startDate->timestamp) / 86400;
        if (!$startDate->isFuture()) {
            if ($startDate->diffInHours(now()) < 3) {
                $usedTime = 0;
            }
        }

        if ($interval === 'day') {
            $dailyPrice = ($price * (365 / $interval_count)) / 365;

            $priceFrUsedTime = $dailyPrice * $usedTime;
        } else if ($interval === 'month') {
            $dailyPrice = ($price * (12 / $interval_count)) / 365;

            $priceFrUsedTime = $dailyPrice * $usedTime;
        } else {
            $dailyPrice = $price / 365;
            $priceFrUsedTime = $dailyPrice * $usedTime;
        }

        return $price - round($priceFrUsedTime, 2);
    }
}
