<?php

namespace Modules\Categories\Repositories;

use Modules\Categories\Models\Category;
use Modules\Base\Repositories\BaseRepository;

class CategoriesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Category::class;

    /**
     * Get a collection of categories under specific category type
     *
     * @param string $type
     * @return collection
     */
    public function whereType($type)
    {
        return $this->getModel()
            ->whereRelation('category_type', 'type', $type)->get();
    }
}