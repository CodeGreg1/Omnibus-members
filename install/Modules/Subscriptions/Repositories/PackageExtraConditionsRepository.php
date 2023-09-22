<?php

namespace Modules\Subscriptions\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Subscriptions\Models\PackageExtraCondition;

class PackageExtraConditionsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = PackageExtraCondition::class;
}
