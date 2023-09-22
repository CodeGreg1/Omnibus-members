<?php

namespace Modules\Settings\Repositories;

use Modules\Settings\Models\Setting;
use Modules\Base\Repositories\BaseRepository;

class SettingsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Setting::class;
}