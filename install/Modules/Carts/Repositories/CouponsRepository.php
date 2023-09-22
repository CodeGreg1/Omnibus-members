<?php

namespace Modules\Carts\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Carts\Models\Coupon;

class CouponsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Coupon::class;
}