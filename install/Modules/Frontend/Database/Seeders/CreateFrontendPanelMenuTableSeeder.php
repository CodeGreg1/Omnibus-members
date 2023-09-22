<?php

namespace Modules\Frontend\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Modules\Menus\Support\MenuType;
use Modules\Languages\Models\Language;
use Illuminate\Database\Eloquent\Model;

class CreateFrontendPanelMenuTableSeeder extends Seeder
{
    /**
     * @var array $frontendLinks
     */
    protected $frontendLinks = [];

    /**
     * @var array $frontendLinks
     */
    protected $frontendLinksGerman = [];

    public function __construct() 
    {
        $this->frontendLinks = [
            [
                'parent_id' => null,
                'icon' => 'fas fa-rss',
                'label' => 'Blogs',
                'link' => 'admin.blogs.index',
                'last_ordering' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-pager',
                'label' => 'Pages',
                'link' => 'admin.pages.index',
                'last_ordering' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-sitemap',
                'label' => 'Sitemap',
                'link' => 'admin.sitemap.index',
                'last_ordering' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-cog',
                'label' => 'Settings',
                'link' => 'admin.frontends.settings.index',
                'last_ordering' => 0,
                'status' => 1,
            ]
        ];


        $this->frontendLinksGerman = [
            [
                'parent_id' => null,
                'icon' => 'fas fa-rss',
                'label' => 'Blogs',
                'link' => 'admin.blogs.index',
                'last_ordering' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-pager',
                'label' => utf8_encode('Seiten'),
                'link' => 'admin.pages.index',
                'last_ordering' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-sitemap',
                'label' => 'Sitemap',
                'link' => 'admin.sitemap.index',
                'last_ordering' => 0,
                'status' => 1,
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-cog',
                'label' => utf8_encode('Einstellungen'),
                'link' => 'admin.frontends.settings.index',
                'last_ordering' => 0,
                'status' => 1,
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
            'icon' => 'fas fa-globe',
            'label' => 'Manage Frontend',
            'link' => 'admin.frontends.settings.index',
            'status' => 1,
            'last_ordering' => 10,
        ]);

        foreach ($this->frontendLinks as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $adminMenu->id,
                    'parent_id' => $adminDepositMenu->id,
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
            'icon' => 'fas fa-globe',
            'label' => utf8_encode('Frontend verwalten'),
            'link' => 'admin.frontends.settings.index',
            'status' => 1,
            'last_ordering' => 10,
        ]);

        foreach ($this->frontendLinksGerman as $key => $link) {
            MenuLink::create(array_merge(
                $link,
                [
                    'menu_id' => $adminMenu->id,
                    'parent_id' => $adminDepositMenu->id,
                    'ordering' => $key
                ]
            ));
        }
    }
}
