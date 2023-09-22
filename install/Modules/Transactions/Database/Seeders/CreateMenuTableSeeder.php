<?php

namespace Modules\Transactions\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Illuminate\Database\Eloquent\Model;

class CreateMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // User Menu
        $userMenu = Menu::where('type', 'User')->first();

        $ordering = MenuLink::whereNull('parent_id')->max('ordering');
        $lastOrdering = MenuLink::whereNull('parent_id')->max('last_ordering');

        MenuLink::create([
            'menu_id' => $userMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-wallet',
            'label' => 'Transactions',
            'link' => 'user.transactions.index',
            'status' => 1,
            'last_ordering' => 4,
        ]);
    }
}
