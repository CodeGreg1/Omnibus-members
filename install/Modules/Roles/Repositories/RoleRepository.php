<?php

namespace Modules\Roles\Repositories;

use Spatie\Permission\Models\Role;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class RoleRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Role::class;

    /**
     * Handle on getting permission by ID
     * 
     * @param int $id
     * 
     * @return Role
     */
    public function getPermissionsById($id) 
    {
        return $this->find($id)->permissions;
    }

    /**
     * Multi-delete data
     * 
     * @param array $ids
     * 
     * @return boolean|null
     */
    public function multiDelete($ids) 
    {
        return (new $this->model)->whereIn('id', $ids)->delete();
    }
}