<?php

namespace Modules\Subscriptions\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Base\Repositories\BaseRepository;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\PricingTable;

class PricingTablesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = PricingTable::class;

    /**
     * Create new resource item
     *
     * @param array $attributes
     */
    public function new(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {

            $table = $this->create([
                'name' => $attributes['name'],
                'description' => $attributes['description'],
                'active' => $this->count() ? 0 : 1
            ]);

            foreach ($attributes['items'] as $key => $item) {
                if ($attributes['featured']) {
                    $price = PackagePrice::find($item['package_price_id']);
                    if ($attributes['featured'] == $price->package_id) {
                        $item['featured'] = 1;
                    }
                }
                $item['order'] = $key + 1;
                $table->items()->create($item);
            }

            return $table;
        }, 3);
    }

    /**
     * Enable pricing table
     *
     * @param PricingTable $entity
     * @return PricingTable
     */
    public function enable($entity)
    {
        $this->getModel()->where('active', 1)->update([
            'active' => 0
        ]);

        $entity->active = 1;
        $entity->save();
        return $entity->fresh();
    }

    /**
     * Disable pricing table
     *
     * @param PricingTable $entity
     * @return PricingTable
     */
    public function disable($entity)
    {
        $entity->active = 0;
        $entity->save();
        return $entity->fresh();
    }

    /**
     * Get active first pricing table
     *
     * @return PricingTable
     */
    public function active()
    {
        return $this->getModel()->where('active', 1)->first();
    }

    /**
     * Update specific resource and its items from storage
     *
     * @param PricingTable $entity
     * @param array $attributes
     */
    public function updateReource(PricingTable $entity, $attributes)
    {
        return DB::transaction(function () use ($entity, $attributes) {

            $table = $this->update(
                $entity,
                [
                    'name' => $attributes['name'],
                    'description' => $attributes['description']
                ]
            );

            $entity = $entity->fresh();

            $packagePricesIds = collect($attributes['items'])->pluck('package_price_id')->toArray();

            $entity->items->map(function ($item) use ($packagePricesIds) {
                if (!in_array(strval($item->package_price_id), $packagePricesIds)) {
                    $item->delete();
                }
            });

            foreach ($attributes['items'] as $key => $item) {
                $item['featured'] = 0;
                if ($attributes['featured']) {
                    $price = PackagePrice::find($item['package_price_id']);
                    if ($attributes['featured'] == $price->package_id) {
                        $item['featured'] = 1;
                    }
                }
                $item['order'] = $key + 1;
                $entity->items()->updateOrCreate(['package_price_id' => $item['package_price_id']], $item);
            }

            return $table;
        }, 3);
    }
}