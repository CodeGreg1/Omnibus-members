<?php

namespace Modules\Base\Repositories;

use Modules\Base\Models\Address;
use Modules\Base\Repositories\BaseRepository;

class AddressRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Address::class;
}