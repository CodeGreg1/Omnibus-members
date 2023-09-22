<?php

namespace Modules\Deposits\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Modules\Models\Module;
use Illuminate\Database\Eloquent\Model;

class AddModuleTableTableSeeder extends Seeder
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
            'name' => 'Deposits',
            'handle' => '',
            'table_name' => 'deposits',
            'attributes' => '{}',
            'is_core' => 1,
            'is_ran_npm' => 0,
            'is_ran_migration' => 0,
            'pro_access' => 0
        ]);
    }
}
