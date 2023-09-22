<?php

namespace Modules\DashboardWidgets\Repositories;

use Modules\DashboardWidgets\Models\DashboardWidget;
use Modules\Base\Repositories\BaseRepository;

class DashboardWidgetsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = DashboardWidget::class;
}