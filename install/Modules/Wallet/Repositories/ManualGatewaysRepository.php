<?php

namespace Modules\Wallet\Repositories;

use Modules\Wallet\Models\ManualGateway;
use Modules\Base\Repositories\BaseRepository;

class ManualGatewaysRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = ManualGateway::class;

    /**
     * Enable manual gateway
     */
    public function enable(ManualGateway $entity)
    {
        $entity->status = 1;
        $entity->save();
    }

    /**
     * Disable manual gateway
     */
    public function disable(ManualGateway $entity)
    {
        $entity->status = 0;
        $entity->save();
    }

    /**
     * Get all active gateways
     */
    public function getActive()
    {
        return $this->newQuery()->where('status', 1)->all();
    }

    /**
     * Filter by status attribute
     *
     * @return self
     */
    public function scopeActive()
    {
        return $this->addScopeQuery(function ($query) {
            return $query->where('status', 1);
        });
    }

    /**
     * Filter by type attribute deposit
     *
     * @return self
     */
    public function scopeDeposit()
    {
        return $this->addScopeQuery(function ($query) {
            return $query->where('type', 'deposit');
        });
    }

    /**
     * Filter by type attribute withdraw
     *
     * @return self
     */
    public function scopeWithdraw()
    {
        return $this->addScopeQuery(function ($query) {
            return $query->where('type', 'withdraw');
        });
    }
}
