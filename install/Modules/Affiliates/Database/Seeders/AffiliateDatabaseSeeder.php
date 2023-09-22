<?php

namespace Modules\Affiliates\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AffiliateDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(AddModuleTableSeeder::class);
        $this->call(CreateMenuTableSeeder::class);
        $this->call(CreateDefaultCommissionTypesTableSeeder::class);
        $this->call(CreateAffiliateEmailTemplatesTableSeeder::class);
    }
}
