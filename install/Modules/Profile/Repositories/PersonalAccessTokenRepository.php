<?php

namespace Modules\Profile\Repositories;

use Laravel\Sanctum\PersonalAccessToken;
use Modules\Base\Repositories\BaseRepository;

class PersonalAccessTokenRepository extends BaseRepository
{
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = PersonalAccessToken::class;
}