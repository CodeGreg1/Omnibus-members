<?php

namespace Modules\Affiliates\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Modules\Models\Module;
use Illuminate\Database\Eloquent\Model;

class AddModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Module::create([
            'name' => 'Affiliates',
            'handle' => '',
            'table_name' => 'affiliate_users',
            'attributes' => '{}',
            'is_core' => 1,
            'is_ran_npm' => 0,
            'is_ran_migration' => 0,
            'pro_access' => 0
        ]);
    }
}
