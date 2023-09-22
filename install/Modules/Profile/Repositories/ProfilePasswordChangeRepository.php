<?php

namespace Modules\Profile\Repositories;

use Modules\Profile\Models\ProfilePasswordChange;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class ProfilePasswordChangeRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = ProfilePasswordChange::class;
}