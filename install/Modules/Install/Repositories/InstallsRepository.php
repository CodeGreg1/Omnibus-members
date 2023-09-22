<?php

namespace Modules\Install\Repositories;

use Modules\Install\Models\Install;
use Modules\Base\Repositories\BaseRepository;

class InstallsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Install::class;
}