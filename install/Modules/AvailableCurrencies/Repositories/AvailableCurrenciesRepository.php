<?php

namespace Modules\AvailableCurrencies\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\AvailableCurrencies\Models\AvailableCurrency;

class AvailableCurrenciesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = AvailableCurrency::class;

    /**
     * Get active available currencies
     */
    public function getActive()
    {
        return (new $this->model)
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Get the first available currency query
     */
    public function firstWhere($where)
    {
        $this->newQuery();

        return $this->where($where)->first();
    }
}