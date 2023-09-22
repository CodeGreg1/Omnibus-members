<?php

namespace Modules\Subscriptions\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Modules\Languages\Models\Language;
use Illuminate\Database\Eloquent\Model;

class CreateUserSubscriptionMenuTableSeeder extends Seeder
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
                'icon' => 'fas fa-money-bill',
                'label' => 'Subscribe Now',
                'link' => 'user.subscriptions.pricings.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-user-check',
                'label' => 'My Subscriptions',
                'link' => 'user.subscriptions.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-money-bill-alt',
                'label' => 'My Payments',
                'link' => 'user.subscriptions.payments.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-money-bill-alt',
                'label' => 'Module Usages',
                'link' => 'user.subscriptions.modules.usage.index',
                'last_ordering' => 0,
                'status' => 1
            ]
        ];

        $this->menuLinksGerman = [
            [
                'icon' => 'fas fa-money-bill',
                'label' => utf8_encode('Abonniere jetzt'),
                'link' => 'user.subscriptions.pricings.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-user-check',
                'label' => utf8_encode('Meine Abonnements'),
                'link' => 'user.subscriptions.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-money-bill-alt',
                'label' => utf8_encode('Meine Zahlungen'),
                'link' => 'user.subscriptions.payments.index',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-money-bill-alt',
                'label' => utf8_encode('Modulverwendungen'),
                'link' => 'user.subscriptions.modules.usage.index',
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
        $userMenu = Menu::where('name', 'User')->first();

        $ordering = MenuLink::whereNull('parent_id')->max('ordering');

        $mainMenu = MenuLink::create([
            'menu_id' => $userMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-user-check',
            'label' => 'Subscriptions',
            'link' => 'user.subscriptions.index',
            'status' => 1,
            'last_ordering' => 1,
        ]);

        foreach ($this->menuLinks as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $userMenu->id,
                    'parent_id' => $mainMenu->id,
                    'ordering' => $key
                ]
            ));
        }
    }

    protected function german() 
    {
        $german = Language::where('title', 'German')->first();

        $userMenu = Menu::where('name', 'User_' . $german->code)->first();
        $ordering = MenuLink::whereNull('parent_id')->where('menu_id', $userMenu->id)->max('ordering');

        $mainMenu = MenuLink::create([
            'menu_id' => $userMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-user-check',
            'label' => utf8_encode('Abonnements'),
            'link' => 'user.subscriptions.index',
            'status' => 1,
            'last_ordering' => 1,
        ]);

        foreach ($this->menuLinksGerman as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $userMenu->id,
                    'parent_id' => $mainMenu->id,
                    'ordering' => $key
                ]
            ));
        }
    }
}