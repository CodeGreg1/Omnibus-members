<?php

namespace Modules\Permissions\Repositories;

use Spatie\Permission\Models\Permission;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class PermissionRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Permission::class;

    /**
     * Valid searchable columns
     *
     * @return array
     */
    protected $searchable = [
        'query' => [
            'name',
            'display_name',
            'description'
        ]
    ];

    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'id',
        'name',
        'display_name',
        'description',
        'created_at'
    ];

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