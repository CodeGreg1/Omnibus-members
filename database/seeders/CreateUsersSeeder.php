<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Modules\Users\Support\UserStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name', 'Admin')->first();

        $userAdmin = User::create([
            'first_name' => 'Ark',
            'last_name' => 'Admin',
            'email' => 'admin@domain.com',
            'username' => 'admin',
            'password' => 'p@ssword',
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
        ]);

        $userAdmin->assignRole($adminRole->id);
    }
}
