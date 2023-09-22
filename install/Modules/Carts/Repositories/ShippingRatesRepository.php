<?php

namespace Modules\Carts\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Carts\Models\ShippingRate;

class ShippingRatesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = ShippingRate::class;

    /**
     * Filter by active attribute
     *
     */
    // public function scopeActive()
    // {
    //     return $this->addScopeQuery(function ($query) {
    //         return $query->where('active', 1);
    //     });
    // }

    public function active()
    {
        return $this->getModel()->where('active', 1);
    }

    /**
     * Get the default shipping rate.
     *
     * @return ShippingRate|null
     */
    public function getDefault()
    {
        $activeRates = $this->active();
        if (!$activeRates->count()) {
            return null;
        }

        $default = $this->active()->where('default', 1)->first();
        if ($default) {
            return $default;
        }

        return $activeRates->first();
    }
}