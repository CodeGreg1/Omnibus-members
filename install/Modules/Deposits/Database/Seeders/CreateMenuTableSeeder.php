<?php

namespace Modules\Deposits\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Modules\Languages\Models\Language;
use Illuminate\Database\Eloquent\Model;

class CreateMenuTableSeeder extends Seeder
{
    /**
     * Admin menu sub links for deposits
     */
    protected $adminMenuLinks = [];

    /**
     * Admin menu sub links for deposits
     */
    protected $userMenuLinks = [];

    /**
     * German admin menu sub links for deposits
     */
    protected $adminMenuLinksGerman = [];

    /**
     * German admin menu sub links for deposits
     */
    protected $userMenuLinksGerman = [];

    public function __construct() 
    {
        $this->adminMenuLinks = [
            [
                'icon' => 'fas fa-list',
                'label' => 'All deposits',
                'link' => 'admin.deposits.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Manual gateways',
                'link' => 'admin.deposits.gateways.manuals.index',
                'last_ordering' => 2,
                'status' => 1
            ]
        ];

        $this->userMenuLinks = [
            [
                'icon' => 'fas fa-list',
                'label' => 'Deposit Money',
                'link' => 'user.deposits.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Deposit History',
                'link' => 'user.deposits.histories.index',
                'last_ordering' => 2,
                'status' => 1
            ]
        ];

        $this->adminMenuLinksGerman = [
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Alle Einzahlungen'),
                'link' => 'admin.deposits.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Manuelle Gateways'),
                'link' => 'admin.deposits.gateways.manuals.index',
                'last_ordering' => 2,
                'status' => 1
            ]
        ];

        $this->userMenuLinksGerman = [
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Geld einzahlen'),
                'link' => 'user.deposits.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Einzahlungshistorie'),
                'link' => 'user.deposits.histories.index',
                'last_ordering' => 2,
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
        Model::unguard();

        $this->english();
        $this->german();
    }

    protected function english() 
    {
        // Admin Menu
        $adminMenu = Menu::where('type', 'Admin')->first();

        $ordering = MenuLink::whereNull('parent_id')->max('ordering');
        $lastOrdering = MenuLink::whereNull('parent_id')->max('last_ordering');

        $adminDepositMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-credit-card',
            'label' => 'Manage Deposits',
            'link' => 'admin.deposits.index',
            'status' => 1,
            'last_ordering' => 3,
        ]);

        foreach ($this->adminMenuLinks as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $adminMenu->id,
                    'parent_id' => $adminDepositMenu->id,
                    'ordering' => $key
                ]
            ));
        }

        // User Menu
        $userMenu = Menu::where('type', 'User')->first();

        $ordering = MenuLink::whereNull('parent_id')->max('ordering');
        $lastOrdering = MenuLink::whereNull('parent_id')->max('last_ordering');

        $userDepositMenu = MenuLink::create([
            'menu_id' => $userMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-credit-card',
            'label' => 'Deposit',
            'link' => 'user.deposits.index',
            'status' => 1,
            'last_ordering' => 2,
        ]);

        foreach ($this->userMenuLinks as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $userMenu->id,
                    'parent_id' => $userDepositMenu->id,
                    'ordering' => $key
                ]
            ));
        }
    }

    protected function german() 
    {
        $german = Language::where('title', 'German')->first();

        // Admin Menu
        $adminMenu = Menu::where('name', 'Admin_' . $german->code)->first();

        $ordering = MenuLink::whereNull('parent_id')->where('menu_id', $adminMenu->id)->max('ordering');

        $adminDepositMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-credit-card',
            'label' => utf8_encode('Einzahlungen verwalten'),
            'link' => 'admin.deposits.index',
            'status' => 1,
            'last_ordering' => 3,
        ]);

        foreach ($this->adminMenuLinksGerman as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $adminMenu->id,
                    'parent_id' => $adminDepositMenu->id,
                    'ordering' => $key
                ]
            ));
        }

        // User Menu
        $userMenu = Menu::where('name', 'User_' . $german->code)->first();

        $ordering = MenuLink::whereNull('parent_id')->where('menu_id', $userMenu->id)->max('ordering');

        $userDepositMenu = MenuLink::create([
            'menu_id' => $userMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-credit-card',
            'label' => utf8_encode('Kaution'),
            'link' => 'user.deposits.index',
            'status' => 1,
            'last_ordering' => 2,
        ]);

        foreach ($this->userMenuLinksGerman as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $userMenu->id,
                    'parent_id' => $userDepositMenu->id,
                    'ordering' => $key
                ]
            ));
        }
    }
}
