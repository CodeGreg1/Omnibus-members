<?php

namespace Modules\Affiliates\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Affiliates\Models\AffiliateReferral;

class AffiliateReferralsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = AffiliateReferral::class;
}
