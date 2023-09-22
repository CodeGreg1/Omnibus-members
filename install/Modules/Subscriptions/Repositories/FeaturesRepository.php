<?php

namespace Modules\Subscriptions\Repositories;

use Modules\Subscriptions\Models\Feature;
use Modules\Base\Repositories\BaseRepository;

class FeaturesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Feature::class;

    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'ordering',
        'id'
    ];
}