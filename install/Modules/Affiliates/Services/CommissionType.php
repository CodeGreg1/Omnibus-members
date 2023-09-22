<?php

namespace Modules\Affiliates\Services;

use Modules\Affiliates\Models\AffiliateCommissionType;

class CommissionType
{
    protected $model = AffiliateCommissionType::class;

    /**
     * Cached commission types
     *
     * @var array
     */
    protected $commission_types_cache;

    /**
     * Cached key name
     *
     * @var string
     */
    protected $cache_key = 'affiliate_commission_types';

    /**
     * Get commission type
     * @param string:null $alias
     * @return Collection
     */
    public function get($alias = null)
    {
        $collection = collect($this->getAll());

        return $collection->get($alias) ?? $collection->first();
    }

    /**
     * Return all commission types.
     *
     * @return array
     */
    public function getAll()
    {
        if ($this->commission_types_cache === null) {
            if (config('app.debug', false) === true) {
                $this->commission_types_cache = $this->getCollection();
            } else {
                $this->commission_types_cache = cache()->rememberForever(
                    $this->cache_key,
                    function () {
                        return $this->getCollection();
                    }
                );
            }
        }

        return $this->commission_types_cache;
    }

    /**
     * Get collection of commission type from database
     *
     * @return Collection
     */
    protected function getCollection()
    {
        $collection = (new $this->model)->get();

        return $collection->keyBy('alias')
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'alias' => $item->alias,
                    'levels' => json_decode($item->levels),
                    'conditions' => json_decode($item->conditions),
                    'active' => $item->active,
                ];
            })
            ->all();
    }

    /**
     * Clear cached commission types.
     */
    public function clearCache()
    {
        cache()->forget($this->cache_key);
        $this->commission_types_cache = null;
    }

    public function hasCondition($alias, $condition)
    {
        $type = $this->get($alias);

        if (!$type) {
            return false;
        }

        if (!isset($type['conditions']->{$condition})) {
            return false;
        }

        return !!intval($type['conditions']->{$condition});
    }
}