<?php

namespace Modules\Affiliates\Repositories;

use Modules\Affiliates\Models\AffiliateUser;
use Modules\Base\Repositories\BaseRepository;

class AffiliatesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = AffiliateUser::class;
}
