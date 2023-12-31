<?php

namespace Modules\Carts\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CartsDatabaseSeeder extends Seeder
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
        $this->call(CreateCheckoutsMenuTableSeederTableSeeder::class);
        // $this->call(CreateCartsMenuTableTableSeeder::class);
    }
}
