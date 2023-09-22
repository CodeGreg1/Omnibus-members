<?php

namespace Modules\Menus\Repositories;

use Modules\Menus\Models\MenuRole;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class MenuRoleRepository extends AbstractRepository
{
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = MenuRole::class;
}