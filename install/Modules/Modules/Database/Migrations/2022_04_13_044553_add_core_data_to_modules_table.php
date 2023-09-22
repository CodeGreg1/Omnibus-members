<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoreDataToModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modules = [
            [
                'name' => 'Auth',
                'table_name' => 'auth',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dashboard',
                'table_name' => 'dashboard',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'EmailTemplates',
                'table_name' => 'email_templates',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Languages',
                'table_name' => 'languages',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Menus',
                'table_name' => 'menus',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Permissions',
                'table_name' => 'permissions',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Modules',
                'table_name' => 'modules',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Profile',
                'table_name' => 'profile',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Roles',
                'table_name' => 'roles',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Users',
                'table_name' => 'users',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'DashboardWidgets',
                'table_name' => 'dashboard_widgets',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Settings',
                'table_name' => 'settings',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AvailableCurrencies',
                'table_name' => 'available_currencies',
                'attributes' => '{}',
                'is_core' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach($modules as $module) {
            $entry = DB::table('modules')->where('name', $module['name'])->first();

            if(is_null($entry)) {
                DB::table('modules')->insert($module);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
