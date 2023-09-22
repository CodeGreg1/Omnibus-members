<?php

namespace Modules\Frontend\Repositories;

use Modules\Frontend\Models\Frontend;
use Modules\Base\Repositories\BaseRepository;

class FrontendsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Frontend::class;
}