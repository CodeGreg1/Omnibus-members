<?php

namespace Modules\Subscriptions\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Modules\Languages\Models\Language;
use Illuminate\Database\Eloquent\Model;

class CreateSubscriptionMenusTableSeeder extends Seeder
{
    /**
     * Subscription menu links
     */
    protected $menuLinks = [];

    /**
     * German subscription menu links
     */
    protected $menuLinksGerman = [];

    public function __construct() 
    {
        $this->menuLinks = [
            [
                'icon' => 'fas fa-user',
                'label' => 'All Subscriptions',
                'link' => 'admin.subscriptions.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Payments',
                'link' => 'admin.subscriptions.payments.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-user',
                'label' => 'Packages',
                'link' => 'admin.subscriptions.packages.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-user',
                'label' => 'Features',
                'link' => 'admin.subscriptions.packages.features.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Pricing Tables',
                'link' => 'admin.subscriptions.pricing-tables.index',
                'last_ordering' => 0,
                'status' => 1
            ]
        ];

        $this->menuLinksGerman = [
            [
                'icon' => 'fas fa-user',
                'label' => utf8_encode('Alle Abonnements'),
                'link' => 'admin.subscriptions.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Zahlungen'),
                'link' => 'admin.subscriptions.payments.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-user',
                'label' => utf8_encode('Pakete'),
                'link' => 'admin.subscriptions.packages.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-user',
                'label' => utf8_encode('Merkmale'),
                'link' => 'admin.subscriptions.packages.features.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Pricing Tables'),
                'link' => 'admin.subscriptions.pricing-tables.index',
                'last_ordering' => 0,
                'status' => 1
            ]
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->english();
        $this->german();

        Model::unguard();
    }

    protected function english() 
    {
        $adminMenu = Menu::where('type', 'Admin')->first();

        $ordering = MenuLink::whereNull('parent_id')->max('ordering');
        $lastOrdering = MenuLink::whereNull('parent_id')->max('last_ordering');

        $mainMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-money-bill',
            'label' => 'Manage Subscriptions',
            'link' => 'admin.subscriptions.index',
            'status' => 1,
            'last_ordering' => 2,
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
    }

    protected function german() 
    {
        $german = Language::where('title', 'German')->first();
        $adminMenu = Menu::where('name', 'Admin_' . $german->code)->first();
        $ordering = MenuLink::whereNull('parent_id')->where('menu_id', $adminMenu->id)->max('ordering');

        $mainMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-money-bill',
            'label' => utf8_encode('Abonnements verwalten'),
            'link' => 'admin.subscriptions.index',
            'status' => 1,
            'last_ordering' => 2,
        ]);

        foreach ($this->menuLinksGerman as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $adminMenu->id,
                    'parent_id' => $mainMenu->id,
                    'ordering' => $key
                ]
            ));
        }
    }
}