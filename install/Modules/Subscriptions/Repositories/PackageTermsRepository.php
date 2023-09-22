<?php

namespace Modules\Subscriptions\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Subscriptions\Models\PackageTerm;
use Modules\AvailableCurrencies\Facades\Currency;

class PackageTermsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = PackageTerm::class;

    public function plans()
    {
        return $this->getModel()->whereHas('prices')
            ->with('prices', function ($query) {
                $query->with(['package', 'package.features']);
            })
            ->get()
            ->map(function ($term) {
                $term = (object) $term->toArray();
                $term->prices =  collect($term->prices)->map(function ($price) {
                    $user_compare_at_price_display = null;
                    $user_amount_display = currency(
                        $price['price'],
                        $price['currency'],
                        Currency::getUserCurrency()
                    );

                    if ($price['compare_at_price']) {
                        $user_compare_at_price_display = currency(
                            $price['compare_at_price'],
                            $price['currency'],
                            Currency::getUserCurrency()
                        );
                    }

                    return (object) [
                        'id' => $price['id'],
                        'featured' => 0,
                        'package' => $price['package']['name'],
                        'user_amount_display' => $user_amount_display,
                        'user_compare_at_price_display' => $user_compare_at_price_display,
                        'type' => $price['type'],
                        'trial_days_count' => $price['trial_days_count'],
                        'features' => collect($price['package']['features'])->map(function ($feature) {
                            return (object) $feature;
                        })
                    ];
                });

                return $term;
            });
    }
}