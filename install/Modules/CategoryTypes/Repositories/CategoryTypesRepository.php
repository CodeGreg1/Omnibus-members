<?php

namespace Modules\CategoryTypes\Repositories;

use Modules\CategoryTypes\Models\CategoryType;
use Modules\Base\Repositories\BaseRepository;

class CategoryTypesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = CategoryType::class;
}