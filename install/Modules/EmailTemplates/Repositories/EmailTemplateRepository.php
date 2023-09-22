<?php

namespace Modules\EmailTemplates\Repositories;

use Spatie\Permission\Models\Role;
use Modules\Base\Repositories\BaseRepository;
use Modules\EmailTemplates\Models\EmailTemplate;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class EmailTemplateRepository extends BaseRepository
{
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = EmailTemplate::class;
}