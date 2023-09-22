<?php

namespace Modules\Wallet\Repositories;

use Modules\Wallet\Models\Wallet;
use Modules\Base\Repositories\BaseRepository;

class WalletsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Wallet::class;
}
