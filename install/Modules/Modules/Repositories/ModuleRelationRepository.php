<?php

namespace Modules\Modules\Repositories;

use Modules\Modules\Models\ModuleRelation;
use Modules\Base\Repositories\BaseRepository;

class ModuleRelationRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = ModuleRelation::class;
}