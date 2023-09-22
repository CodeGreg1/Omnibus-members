<?php

namespace Modules\Affiliates\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Affiliates\Models\AffiliateReferralLevel;

class AffiliateReferralLevelsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = AffiliateReferralLevel::class;
}
