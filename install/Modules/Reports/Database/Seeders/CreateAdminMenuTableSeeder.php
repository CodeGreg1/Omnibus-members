<?php

namespace Modules\Reports\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Modules\Languages\Models\Language;
use Illuminate\Database\Eloquent\Model;

class CreateAdminMenuTableSeeder extends Seeder
{
    /**
     * Reports menu links
     */
    protected $menuLinks = [];

    /**
     * German reports menu links
     */
    protected $menuLinksGerman = [];

    public function __construct() 
    {
        $this->menuLinks = [
            [
                'icon' => 'fas fa-chart-bar',
                'label' => 'Billing',
                'link' => 'admin.reports.billing',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-chart-bar',
                'label' => 'Transactions',
                'link' => 'admin.transactions.reports.index',
                'last_ordering' => 1,
                'status' => 1
            ]
        ];

        $this->menuLinksGerman = [
            [
                'icon' => 'fas fa-chart-bar',
                'label' => utf8_encode('Abrechnung'),
                'link' => 'admin.reports.billing',
                'last_ordering' => 0,
                'status' => 1
            ],
            [
                'icon' => 'fas fa-chart-bar',
                'label' => utf8_encode('Transaktionen'),
                'link' => 'admin.transactions.reports.index',
                'last_ordering' => 1,
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
        $adminMenu = Menu::where('type', 'Admin')->first();

        $ordering = MenuLink::whereNull('parent_id')->max('ordering');

        $mainMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-chart-bar',
            'label' => 'Manage Reports',
            'link' => 'admin.reports.index',
            'status' => 1,
            'last_ordering' => 7,
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
            'icon' => 'fas fa-chart-bar',
            'label' => utf8_encode('Berichte verwalten'),
            'link' => 'admin.reports.index',
            'status' => 1,
            'last_ordering' => 7,
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
