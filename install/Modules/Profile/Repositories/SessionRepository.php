<?php

namespace Modules\Profile\Repositories;

use Modules\Profile\Models\Session;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class SessionRepository extends AbstractRepository
{
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Session::class;

    /**
     * Invalidate all
     * 
     * @return bool|null
     */
    public function invalidateAllDevices() 
    {
        return (new $this->model)->where('user_id', auth()->id())->delete();
    }
}