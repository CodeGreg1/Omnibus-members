<?php

namespace Modules\Products\Repositories;

use Modules\Products\Models\Product;
use Modules\Base\Repositories\BaseRepository;

class ProductsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Product::class;
}