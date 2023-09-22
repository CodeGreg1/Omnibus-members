<?php

namespace Modules\Deposits\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DepositsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(AddModuleTableTableSeeder::class);
        $this->call(CreateMenuTableSeeder::class);
        $this->call(CreateDepositEmailTemplatesTableSeeder::class);
    }
}
