<?php

namespace Modules\Pages\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PagesDatabaseSeeder extends Seeder
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
        $this->call(CreatePageContactUsMessageTableSeeder::class);
        $this->call(CreateAboutUsPageTableSeeder::class);
        $this->call(CreateContactUsPageTableSeeder::class);
        $this->call(CreatePricingPageTableSeeder::class);
        $this->call(CreateFaqPageTableSeeder::class);
        $this->call(CreateDashboardPageTableSeeder::class);
        $this->call(CreatePrivacyPolicyPageTableSeeder::class);
        $this->call(CreateTermsAndConditionsPageTableSeeder::class);
    }
}
