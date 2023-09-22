<?php

namespace Modules\Subscriptions\Repositories;

use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Modules\Base\Repositories\BaseRepository;
use Modules\Subscriptions\Models\PackageModuleLimit;

class PackageModuleLimitsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = PackageModuleLimit::class;

    /**
     * Update a given resourse
     *
     * @param Package $package
     * @param array $attributes
     *
     * @return PackageModuleLimit|mixed
     */
    public function updateOrCreateResource($package, array $attributes)
    {
        $limit = $this->getModel()->updateOrCreate([
            'package_id' => $package->id,
            'permission_id' => $attributes['permission_id']
        ], $attributes);

        $module = explode('.', $limit->permission->name);
        $action = Arr::last($module);
        $childLimit = false;
        if ($action === 'index') {
            $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
            $childPermission = Permission::where(['name' => $parateRoute . '.datatable', 'guard_name' => 'web'])->first();
            if ($childPermission) {
                $attributes['permission_id'] = $childPermission->id;
                $childLimit = $this->getModel()->updateOrCreate([
                    'package_id' => $package->id,
                    'permission_id' => $childPermission->id
                ], $attributes);
            }
        }

        if ($action === 'create') {
            $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
            $childPermission = Permission::where(['name' => $parateRoute . '.store', 'guard_name' => 'web'])->first();
            if ($childPermission) {
                $attributes['permission_id'] = $childPermission->id;
                $childLimit = $this->getModel()->updateOrCreate([
                    'package_id' => $package->id,
                    'permission_id' => $childPermission->id
                ], $attributes);
            }
        }

        if ($action === 'edit') {
            $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
            $childPermission = Permission::where(['name' => $parateRoute . '.update', 'guard_name' => 'web'])->first();
            if ($childPermission) {
                $attributes['permission_id'] = $childPermission->id;
                $childLimit = $this->getModel()->updateOrCreate([
                    'package_id' => $package->id,
                    'permission_id' => $childPermission->id
                ], $attributes);
            }
        }

        if ($action === 'delete') {
            $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
            $childPermission = Permission::where(['name' => $parateRoute . '.multi-delete', 'guard_name' => 'web'])->first();
            if ($childPermission) {
                $attributes['permission_id'] = $childPermission->id;
                $childLimit = $this->getModel()->updateOrCreate([
                    'package_id' => $package->id,
                    'permission_id' => $childPermission->id
                ], $attributes);
            }
        }

        if (!$limit->limit) {
            $limit->counter()->delete();
        }

        if ($childLimit && !$limit->limit) {
            $limit->counter()->delete();
        }

        return $limit;
    }

    /**
     * Remove all resources from storage
     *
     * @param Package $package
     * @param array $attributes
     *
     * @return boolean
     */
    public function deleteAllItems($package, $limits)
    {
        $package->moduleLimits()
            ->whereNotIn('permission_id', collect($limits)->pluck('permission_id')->toArray())
            ->get()
            ->each(function ($limit) use ($package) {
                $module = explode('.', $limit->permission->name);
                $action = Arr::last($module);
                $childLimit = false;
                if ($action === 'index') {
                    $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
                    $childPermission = Permission::where(['name' => $parateRoute . '.datatable', 'guard_name' => 'web'])->first();
                    if ($childPermission) {
                        $childLimit = $package->moduleLimits()->where(
                            'permission_id',
                            $childPermission->id
                        )->first();
                    }
                }

                if ($action === 'create') {
                    $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
                    $childPermission = Permission::where(['name' => $parateRoute . '.store', 'guard_name' => 'web'])->first();
                    if ($childPermission) {
                        $childLimit = $package->moduleLimits()->where(
                            'permission_id',
                            $childPermission->id
                        )->first();
                    }
                }

                if ($action === 'edit') {
                    $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
                    $childPermission = Permission::where(['name' => $parateRoute . '.update', 'guard_name' => 'web'])->first();
                    if ($childPermission) {
                        $childLimit = $package->moduleLimits()->where(
                            'permission_id',
                            $childPermission->id
                        )->first();
                    }
                }

                if ($action === 'delete') {
                    $parateRoute = implode(".", array_slice($module, 0, count($module) - 1));
                    $childPermission = Permission::where(['name' => $parateRoute . '.multi-delete', 'guard_name' => 'web'])->first();
                    if ($childPermission) {
                        $childLimit = $package->moduleLimits()->where(
                            'permission_id',
                            $childPermission->id
                        )->first();
                    }
                }

                if ($childLimit) {
                    $childLimit->delete();
                }

                $limit->delete();
            });
    }
}