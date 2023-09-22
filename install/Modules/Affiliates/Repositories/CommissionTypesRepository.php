<?php

namespace Modules\Affiliates\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Affiliates\Models\AffiliateCommissionType;

class CommissionTypesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = AffiliateCommissionType::class;
}
