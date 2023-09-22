<?php

namespace Modules\Subscriptions\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Modules\Subscriptions\Models\Package;
use Modules\Base\Repositories\BaseRepository;
use Modules\Subscriptions\Events\PackageCreated;
use Modules\Subscriptions\Events\PackageDeleted;
use Modules\Subscriptions\Exceptions\PackagePriceException;
use Spatie\Permission\Contracts\Permission as ContractsPermission;
use Modules\Subscriptions\Exceptions\UnprocessablePackagesException;

class PackagesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Package::class;

    public function store(array $attributes)
    {
        $package = DB::transaction(function () use ($attributes) {
            $package = $this->create($attributes);

            $prices = collect($attributes['prices']);

            $prices->map(function ($price) use ($package) {
                $package->prices()->create(
                    array_merge($price, [
                        'price' => $price['price'],
                        'compare_at_price' => $price['compare_at_price'],
                        'trial_days_count' => intval($price['trial_days_count'])
                    ])
                );
            });

            if (isset($attributes['features']) && count($attributes['features'])) {
                foreach ($attributes['features'] as $key => $value) {
                    $package->features()->attach($value, ['order' => $key + 1]);
                }
            }

            if (isset($attributes['limits']) && count($attributes['limits'])) {
                $permissionIds = Arr::pluck($attributes['limits'], 'permission_id');
                $permissions = Permission::whereIn('id', $permissionIds)->get();
                collect($attributes['limits'])->map(function ($item) use ($package, $permissions) {
                    $item['package_id'] = $package->id;
                    $permission = $permissions->first(function ($p) use ($item) {
                        return $p->id === intval($item['permission_id']);
                    });


                    if ($permission) {
                        $module = explode('.', $permission->name);
                        $action = Arr::last($module);
                        if ($action === 'index') {
                            $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
                            $childPermission = Permission::where(['name' => $parateRoute . '.datatable', 'guard_name' => 'web'])->first();
                            if ($childPermission) {
                                $item['permission_id'] = $childPermission->id;
                                $package->moduleLimits()->create($item);
                            }
                        }

                        if ($action === 'create') {
                            $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
                            $childPermission = Permission::where(['name' => $parateRoute . '.store', 'guard_name' => 'web'])->first();
                            if ($childPermission) {
                                $item['permission_id'] = $childPermission->id;
                                $package->moduleLimits()->create($item);
                            }
                        }

                        if ($action === 'edit') {
                            $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
                            $childPermission = Permission::where(['name' => $parateRoute . '.update', 'guard_name' => 'web'])->first();
                            if ($childPermission) {
                                $item['permission_id'] = $childPermission->id;
                                $package->moduleLimits()->create($item);
                            }
                        }

                        if ($action === 'delete') {
                            $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
                            $childPermission = Permission::where(['name' => $parateRoute . '.multi-delete', 'guard_name' => 'web'])->first();
                            if ($childPermission) {
                                $item['permission_id'] = $childPermission->id;
                                $package->moduleLimits()->create($item);
                            }
                        }

                        $item['permission_id'] = $permission->id;
                        $package->moduleLimits()->create($item);
                    }
                });
            }

            if (isset($attributes['extra_conditions']) && count($attributes['extra_conditions'])) {
                foreach ($attributes['extra_conditions'] as $key => $value) {
                    $package->extraConditions()->create($value);
                }
            }

            return $package;
        }, 3);

        PackageCreated::dispatch($package);

        return $package;
    }

    public function destroy($entity)
    {
        return DB::transaction(function () use ($entity) {
            $entity->prices->map(function ($price) use ($entity) {
                if ($price->hasSubscriptions()) {
                    throw UnprocessablePackagesException::hasSubscriptions([$entity->name]);
                }

                try {
                    $price->delete();
                } catch (\Exception $e) {
                    throw PackagePriceException::cannotBeDelete($e->getMessage());
                }
            });

            $entity->delete();

            try {
                PackageDeleted::dispatch($entity);
            } catch (\Exception $e) {
                report($e);
            }
        });
    }
}