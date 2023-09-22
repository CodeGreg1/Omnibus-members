<?php

namespace Modules\Orders\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Modules\Models\Module;

class AddModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::create([
            'name' => 'Orders',
            'handle' => '',
            'table_name' => 'orders',
            'attributes' => '{}',
            'is_core' => 1,
            'is_ran_npm' => 0,
            'is_ran_migration' => 0,
            'pro_access' => 0
        ]);
    }
}
