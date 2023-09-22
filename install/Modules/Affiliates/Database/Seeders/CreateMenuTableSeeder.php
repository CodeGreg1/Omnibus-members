<?php

namespace Modules\Affiliates\Database\Seeders;

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
     * German admin menu sub links for deposits
     */
    protected $adminMenuLinksGerman = [];

    public function __construct() 
    {
        $this->adminMenuLinks = [
            [
                'icon' => 'fas fa-list',
                'label' => 'Affiliate Users',
                'link' => 'admin.affiliates.users.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Affiliate Referrals',
                'link' => 'admin.affiliates.referrals.index',
                'last_ordering' => 2,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Commissions',
                'link' => 'admin.affiliates.commissions.index',
                'last_ordering' => 3,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => 'Commission Types',
                'link' => 'admin.affiliates.commission-types.index',
                'last_ordering' => 3,
                'status' => 1
            ]
        ];

        $this->adminMenuLinksGerman = [
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Affiliate-Benutzer'),
                'link' => 'admin.affiliates.users.index',
                'last_ordering' => 1,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Affiliate-Empfehlungen'),
                'link' => 'admin.affiliates.referrals.index',
                'last_ordering' => 2,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Provisionen'),
                'link' => 'admin.affiliates.commissions.index',
                'last_ordering' => 3,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-list',
                'label' => utf8_encode('Provisionsarten'),
                'link' => 'admin.affiliates.commission-types.index',
                'last_ordering' => 3,
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
            'icon' => 'fas fa-handshake',
            'label' => 'Manage Affiliate',
            'link' => 'admin.affiliates.users.index',
            'status' => 1,
            'last_ordering' => 5,
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

        MenuLink::create([
            'menu_id' => $userMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-handshake',
            'label' => 'Commissions',
            'link' => 'user.commissions.index',
            'status' => 1,
            'last_ordering' => 5,
        ]);
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
            'icon' => 'fas fa-handshake',
            'label' => utf8_encode('Affiliate verwalten'),
            'link' => 'admin.affiliates.users.index',
            'status' => 1,
            'last_ordering' => 5,
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

        MenuLink::create([
            'menu_id' => $userMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-handshake',
            'label' => utf8_encode('Provisionen'),
            'link' => 'user.commissions.index',
            'status' => 1,
            'last_ordering' => 5,
        ]);
    }
}
