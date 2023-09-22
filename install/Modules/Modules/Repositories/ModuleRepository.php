<?php

namespace Modules\Modules\Repositories;

use Modules\Modules\Models\Module;
use Modules\Base\Repositories\BaseRepository;

class ModuleRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Module::class;

    /**
     * @var array NPM_STATUSES
     */
    const NPM_STATUSES = [
        'PENDING' => 0,
        'DONE' => 1,
        'ONGOING' => 2
    ];

    /**
     * Update all modules pending to ongoing
     * 
     * @var array $modules - Module IDs in array
     * 
     * @return boolean
     */
    public function setPendingOngoingNpm($modules) 
    {
        $model = (new $this->model)
            ->query()
            ->whereIn('id', $modules)
            ->update(['is_ran_npm' => self::NPM_STATUSES['ONGOING']]);

        // reset cache
        $this->flushQueryCache();

        return $model;
    }

    /**
     * Update all modules ongoing to done
     * 
     * @var array $modules - Module IDs in array
     * 
     * @return boolean
     */
    public function setOngoingDoneNpm($modules) 
    {
        $model = (new $this->model)
            ->query()
            ->whereIn('id', $modules)
            ->update(['is_ran_npm' => self::NPM_STATUSES['DONE']]);

        // reset cache
        $this->flushQueryCache();

        return $model;
    }

    /**
     * Update all modules set all migration ran all
     * 
     * @return boolean
     */
    public function setMigrationRanAll() 
    {
        $model = (new $this->model)
            ->query()
            ->where('is_ran_migration', 0)
            ->update(['is_ran_migration' => 1]);

        // reset cache
        $this->flushQueryCache();

        return $model;
    }

    /**
     * Get pro access modules
     * 
     * @return array
     */
    public function proAccess() 
    {
        return (new $this->model)
            ->whereProAccess(1)
            ->pluck('name')
            ->toArray();
    }
}