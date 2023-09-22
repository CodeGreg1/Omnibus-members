<?php

namespace Modules\Pages\Repositories;

use Modules\Pages\Models\Page;
use Modules\Base\Repositories\BaseRepository;

class PagesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Page::class;
}
