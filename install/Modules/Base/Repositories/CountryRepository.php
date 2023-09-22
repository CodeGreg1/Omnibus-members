<?php

namespace Modules\Base\Repositories;

use Modules\Base\Models\Country;
use Modules\Base\Repositories\BaseRepository;

class CountryRepository extends BaseRepository
{
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Country::class;
}