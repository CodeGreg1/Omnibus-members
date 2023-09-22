<?php

namespace Modules\Base\Repositories;

use Modules\Base\Models\Currency;
use Modules\Base\Repositories\BaseRepository;

class CurrenciesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Currency::class;
}
