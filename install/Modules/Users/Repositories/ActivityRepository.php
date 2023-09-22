<?php

namespace Modules\Users\Repositories;

use Modules\Users\Models\Activity;
use Modules\Base\Repositories\BaseRepository;

class ActivityRepository extends BaseRepository
{   
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Activity::class;

    /**
     * @var int $latestLimit
     */
    protected $latestLimit = 10;

    /**
     * Get user latest activity by id/by current logged
     * 
     * @param int|null $id
     * 
     * @return Activity
     */
    public function getUserLatest($id = null) 
    {
        $model = (new $this->model);

        if(is_null($id)) {
            $model = $model->where('causer_id', auth()->id());
        } else {
            $model = $model->where('causer_id', $id);
        }
            
        $model = $model->limit($this->latestLimit)
            ->latest()
            ->get();

        return $model;
    }
}