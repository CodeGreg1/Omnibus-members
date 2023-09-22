<?php

namespace Modules\Carts\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Illuminate\Database\Eloquent\Model;

class CreateCheckoutsMenuTableSeederTableSeeder extends Seeder
{
    /**
     * Subscription menu links
     */
    protected $menuLinks = [
        [
            'icon' => 'fas fa-list',
            'label' => 'Coupons',
            'link' => 'admin.coupons.index',
            'last_ordering' => 0,
            'status' => 1
        ],
        [
            'icon' => 'fas fa-user',
            'label' => 'Shipping rates',
            'link' => 'admin.shipping-rates.index',
            'last_ordering' => 0,
            'status' => 1
        ],
        [
            'icon' => 'fas fa-user',
            'label' => 'Tax rates',
            'link' => 'admin.tax-rates.index',
            'last_ordering' => 0,
            'status' => 1
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$adminMenu = Menu::where('type', 'Admin')->first();

        $ordering = MenuLink::whereNull('parent_id')->max('ordering');
        $lastOrdering = MenuLink::whereNull('parent_id')->max('last_ordering');

        $mainMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-cash-register',
            'label' => 'Checkouts',
            'link' => 'admin.coupons.index',
            'status' => 1,
            'last_ordering' => $lastOrdering + 1,
        ]);

        foreach ($this->menuLinks as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $adminMenu->id,
                    'parent_id' => $mainMenu->id,
                    'ordering' => $key
                ]
            ));
        }

        Model::unguard();*/
    }
}