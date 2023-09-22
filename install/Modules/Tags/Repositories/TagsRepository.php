<?php

namespace Modules\Tags\Repositories;

use Modules\Tags\Models\Tag;
use Modules\Base\Repositories\BaseRepository;

class TagsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Tag::class;

    public function firstOrCreate(array $attributes = [], array $values = [])
    {
        return $this->getModel()->firstOrCreate($attributes, $values);
    }

    public function search($name)
    {
        return $this->getModel()
            ->where('name', 'LIKE', '%' . $name . '%')
            ->take(10)
            ->get();
    }
}
