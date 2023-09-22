<?php

namespace Modules\Subscriptions\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\AvailableCurrencies\Facades\Currency;

class PackagePricesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = PackagePrice::class;

    public function whereFirstOrFail($where)
    {
        $this->newQuery();
        return $this->where($where)->firstOrFail();
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  string  $column
     * @param  string  $value
     * @param  array  $relations
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function findFirstWith($where, $relations)
    {
        $this->newQuery();
        return $this->query->where($where)->with($relations)->firstOrFail();
    }

    public function getLifetimePrices()
    {
        $this->newQuery();

        return $this->where(['type' => 'onetime'])
            ->with(['package', 'package.features'])
            ->get()
            ->map(function ($price) {
                $user_compare_at_price_display = null;
                $user_amount_display = currency(
                    $price->price,
                    $this->currency,
                    Currency::getUserCurrency()
                );

                if ($price->compare_at_price) {
                    $user_compare_at_price_display = currency(
                        $price->compare_at_price,
                        $this->currency,
                        Currency::getUserCurrency()
                    );
                }

                return (object) [
                    'id' => $price->id,
                    'featured' => $price->package->featured,
                    'package' => $price->package->name,
                    'user_amount_display' => $user_amount_display,
                    'user_compare_at_price_display' => $user_compare_at_price_display,
                    'type' => $price->type,
                    'trial_days_count' => $price->trial_days_count,
                    'features' => $price->package->features
                ];

                return $price;
            });
    }

    public function getPricingTable()
    {
        return $this->getModel()->has('gatewayPrices')
            ->with(['package'])
            ->get();
    }
}