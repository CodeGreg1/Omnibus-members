<?php

namespace Modules\Transactions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TransactionsDatabaseSeeder extends Seeder
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
    }
}
