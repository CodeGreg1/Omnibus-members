<?php

namespace Modules\Subscriptions\Support;

use Illuminate\Support\Str;
use Modules\Roles\Support\ExtractModuleName;
use Modules\Roles\Support\ExcludedPermissions;
use Modules\Modules\Repositories\ModuleRepository;

trait PermissionValues
{
    /**
     * Get valid permission for packages
     *
     * @param Permission $permissions
     *
     * @return array
     */
    protected function getValidValues($permissions)
    {
        $resultPermissions = [];
        $proModules = (new ModuleRepository)->proAccess();
        if (!count($proModules)) {
            return $resultPermissions;
        }

        foreach ($permissions as $permission) {
            $module = explode('.', $permission->name);
            if ($module[0] === 'user') {
                $moduleName = (new ExtractModuleName($module))->get();
                $studlyName = Str::studly($moduleName);

                if (in_array($studlyName, $proModules) && !in_array($permission->name, ExcludedPermissions::lists())) {
                    $resultPermissions[] = $permission;
                }
            }
        }

        return collect($resultPermissions);
    }

    /**
     * Handle parsing permission
     *
     * @param Permission $permissions
     *
     * @return array
     */
    protected function handleParsingPermissionValues($permissions)
    {
        $resultPermissions = [];

        foreach ($permissions as $permission) {
            $module = explode('.', $permission->name);

            $moduleName = (new ExtractModuleName($module))->get();

            if (!in_array($moduleName, ExcludedPermissions::lists())) {
                $resultPermissions[$moduleName][$permission->name]['name'] = $permission->name;
                $resultPermissions[$moduleName][$permission->name]['display_name'] = $permission->display_name;
                $resultPermissions[$moduleName][$permission->name]['description'] = $permission->description;
                $resultPermissions[$moduleName][$permission->name]['guard'] = $permission->web;
            }
        }

        return $resultPermissions;
    }
}