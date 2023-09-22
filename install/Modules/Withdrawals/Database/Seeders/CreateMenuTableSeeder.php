<?php

namespace Modules\Withdrawals\Database\Seeders;

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
                'icon' => 'fas fa-tasks',
                'label' => 'All withdrawals',
                'link' => 'admin.withdrawals.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Withdrawal methods',
                'link' => 'admin.withdrawals.methods.index',
                'last_ordering' => 2,
                'status' => 1
            ]
        ];

        $this->userMenuLinks = [
            [
                'icon' => 'fas fa-list',
                'label' => 'Withdraw Money',
                'link' => 'user.withdrawals.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Withdraw History',
                'link' => 'user.withdrawals.histories.index',
                'last_ordering' => 2,
                'status' => 1
            ]
        ];



        $this->adminMenuLinksGerman = [
            [
                'icon' => 'fas fa-tasks',
                'label' => 'Alle Entnahmen',
                'link' => 'admin.withdrawals.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Auszahlungsmethoden',
                'link' => 'admin.withdrawals.methods.index',
                'last_ordering' => 2,
                'status' => 1
            ]
        ];

        $this->userMenuLinksGerman = [
            [
                'icon' => 'fas fa-list',
                'label' => 'Geld abheben',
                'link' => 'user.withdrawals.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Verlauf zur&uuml;ckziehen',
                'link' => 'user.withdrawals.histories.index',
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
        $adminOrdering = MenuLink::whereNull('parent_id')->max('ordering');

        $adminDepositMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $adminOrdering + 1,
            'icon' => 'fas fa-tasks',
            'label' => 'Manage Withdrawals',
            'link' => 'admin.withdrawals.index',
            'status' => 1,
            'last_ordering' => 4,
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
        $userOrdering = MenuLink::whereNull('parent_id')->max('ordering');

        $userDepositMenu = MenuLink::create([
            'menu_id' => $userMenu->id,
            'ordering' => $userOrdering + 1,
            'icon' => 'fas fa-tasks',
            'label' => 'Withdraw',
            'link' => 'user.withdrawals.index',
            'status' => 1,
            'last_ordering' => 3,
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
        $adminOrdering = MenuLink::whereNull('parent_id')->where('menu_id', $adminMenu->id)->max('ordering');

        $adminDepositMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $adminOrdering + 1,
            'icon' => 'fas fa-tasks',
            'label' => 'Abhebungen verwalten',
            'link' => 'admin.withdrawals.index',
            'status' => 1,
            'last_ordering' => 4,
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
        $userOrdering = MenuLink::whereNull('parent_id')->where('menu_id', $userMenu->id)->max('ordering');

        $userDepositMenu = MenuLink::create([
            'menu_id' => $userMenu->id,
            'ordering' => $userOrdering + 1,
            'icon' => 'fas fa-tasks',
            'label' => 'Zur&uuml;ckziehen',
            'link' => 'user.withdrawals.index',
            'status' => 1,
            'last_ordering' => 3,
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
