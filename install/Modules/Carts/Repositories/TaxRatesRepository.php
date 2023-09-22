<?php

namespace Modules\Carts\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Carts\Models\TaxRate;

class TaxRatesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = TaxRate::class;

    /**
     * Get all active tax rates
     *
     * @return Collection
     */
    public function getActive()
    {
        return $this->getModel()->where('active', 1)->get();
    }
}