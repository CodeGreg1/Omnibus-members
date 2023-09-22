<?php

namespace Modules\Carts\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Illuminate\Database\Eloquent\Model;

class CreateCartsMenuTableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminMenu = Menu::where('type', 'User')->first();

        $ordering = MenuLink::whereNull('parent_id')->max('ordering');
        $lastOrdering = MenuLink::whereNull('parent_id')->max('last_ordering');

        MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-shopping-cart',
            'label' => 'My cart',
            'link' => 'user.carts.index',
            'status' => 1,
            'last_ordering' => $lastOrdering + 1,
        ]);

        Model::unguard();
    }
}