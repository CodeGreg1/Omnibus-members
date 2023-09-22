<?php

namespace Modules\Subscriptions\Services\Service\Invoice\Clients;

use Illuminate\Support\Str;
use Modules\Cashier\Facades\Cashier;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\Subscription;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Subscriptions\Services\Service\Invoice\InvoiceServiceClient;

class Stripe extends InvoiceServiceClient
{
    protected $gateway = 'stripe';

    public function upcoming(Subscription $subscription, PackagePrice $price)
    {
        $data = [];
        $currency = $subscription->item->price->currency;
        $data['currency'] = $currency;
        $stripeSubscription = $this->api->subscriptions->retrieve($subscription->ref_profile_id);
        $newItemPrice = $price->getUnitPrice(false, $currency);

        $currentPrice = $subscription->item->getUnitPrice(
            false,
            $currency
        );

        if ($subscription->onTrial()) {
            return [
                'amount_due' => $newItemPrice,
                'amount_due_display' => currency(
                    $newItemPrice,
                    $currency,
                    Currency::getUserCurrency()
                )
            ];
        }

        $payload = [
            'customer' => $stripeSubscription->customer,
            'subscription' => $stripeSubscription->id,
            'subscription_trial_end' => 'now',
            'subscription_billing_cycle_anchor' => 'now',
            'subscription_items' => [
                [
                    'id' => $stripeSubscription->items->data[0]->id,
                    'price_data' => [
                        'currency' => Str::lower($price->currency),
                        'product' => $this->api->service->getConfig('product_key'),
                        'unit_amount_decimal' => amount_to_cents($price->price),
                        'recurring' => [
                            'interval' => $price->term->interval,
                            'interval_count' => $price->term->interval_count
                        ]
                    ]
                ]
            ]
        ];

        $invoice = $this->api->invoices->upcoming($payload);
        // dd($invoice);
        if ($invoice) {
            $normal_amount_due = currency(
                normalize_amount($invoice->amount_due),
                $invoice->currency,
                $currency,
                false,
            );

            if ($normal_amount_due) {
                if ($normal_amount_due > $currentPrice) {
                    $amountDue = $normal_amount_due > $newItemPrice
                        ? ($normal_amount_due - $newItemPrice)
                        : $normal_amount_due;
                } else {
                    $amountDue = 0;
                    $data['credit'] = $newItemPrice - $normal_amount_due;
                    $creditLastBill = currency(
                        $normal_amount_due,
                        $currency,
                        Currency::getUserCurrency()
                    );
                    $data['credit_bill_desc'] = __('Next invoice is :amount', [
                        'amount' => $creditLastBill
                    ]);
                    $data['credit_bill_coverage'] = 0;
                    $data['credit_last_bill'] = $normal_amount_due;
                    $data['credit_display'] = currency(
                        $data['credit'],
                        $currency,
                        Currency::getUserCurrency()
                    );
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
            } else {
                if ($invoice->ending_balance < 0) {
                    $amountDue = 0;
                    $data['credit'] = $newItemPrice + currency(
                        normalize_amount(abs($invoice->ending_balance)),
                        $invoice->currency,
                        $currency,
                        false
                    );

                    $totalCreditCyleCoverage = intval($data['credit'] / $newItemPrice);
                    $remaingCoveredAmount = $data['credit'] - ($newItemPrice * $totalCreditCyleCoverage);
                    $creditLastBill = currency(
                        $remaingCoveredAmount,
                        $currency,
                        Currency::getUserCurrency()
                    );
                    $data['credit_bill_desc'] = __('If not cancelled, remaining balance covers :count billing cycles and :amount', [
                        'count' => $totalCreditCyleCoverage,
                        'amount' => $creditLastBill
                    ]);
                    $data['credit_display'] = currency(
                        $data['credit'],
                        $currency,
                        Currency::getUserCurrency()
                    );
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
                    $amountDue = $newItemPrice;
                }
            }

            $now = now()->format('d M, Y');
            $newPriceUnusedTimeObject = collect($invoice->lines->data)
                ->first(function ($lineItem, $key) use ($price) {
                    return $lineItem->price->unit_amount === amount_to_cents($price->price) && $lineItem->amount !== 0 && $lineItem->proration;
                });

            if ($newPriceUnusedTimeObject) {
                $newPriceUnusedTimeAmount = currency(
                    normalize_amount($newPriceUnusedTimeObject->amount),
                    $newPriceUnusedTimeObject->currency,
                    $currency,
                    false
                );

                $data['lines'][] = [
                    'description' => __('Remaining time on :package after :date', [
                        'package' => $price->package->name,
                        'date' => $now
                    ]),
                    'amount' => $newPriceUnusedTimeAmount,
                    'amount_display' => currency(
                        abs($newPriceUnusedTimeAmount),
                        $currency,
                        Currency::getUserCurrency()
                    )
                ];
            }

            $currentPriceUnusedTimeObject = collect($invoice->lines->data)
                ->first(function ($lineItem, $key) use ($subscription) {
                    return $lineItem->price->unit_amount === amount_to_cents($subscription->item->price->price) && $lineItem->amount !== 0  && $lineItem->proration;
                });

            if ($currentPriceUnusedTimeObject) {
                $currentPriceUnusedTimeAmount = currency(
                    normalize_amount($currentPriceUnusedTimeObject->amount),
                    $currentPriceUnusedTimeObject->currency,
                    $currency,
                    false
                );

                $data['lines'][] = [
                    'description' => __('Unused time on :package after :date', [
                        'package' => $subscription->item->price->package->name,
                        'date' => $now
                    ]),
                    'amount' => $currentPriceUnusedTimeAmount,
                    'amount_display' => '-' . currency(
                        abs($currentPriceUnusedTimeAmount),
                        $currency,
                        Currency::getUserCurrency()
                    )
                ];
            }

            $data['amount_due'] = $amountDue;
            $data['amount_due_display'] = currency(
                $amountDue,
                $currency,
                Currency::getUserCurrency()
            );
            return $data;
        }

        return null;
    }
}
