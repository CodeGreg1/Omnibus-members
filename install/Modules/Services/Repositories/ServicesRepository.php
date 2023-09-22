<?php

namespace Modules\Services\Repositories;

use Modules\Services\Models\Service;
use Modules\Base\Repositories\BaseRepository;

class ServicesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Service::class;
}