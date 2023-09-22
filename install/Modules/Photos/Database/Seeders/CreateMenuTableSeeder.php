<?php

namespace Modules\Photos\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Modules\Languages\Models\Language;
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

        $this->english();
        $this->german();
    }

    protected function english() 
    {
        // Admin Menu
        $adminMenu = Menu::where('type', 'Admin')->first();

        $ordering = MenuLink::whereNull('parent_id')->max('ordering');
        $lastOrdering = MenuLink::whereNull('parent_id')->max('last_ordering');

        $adminMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-images',
            'label' => 'Manage Photos',
            'link' => 'admin.photos.index',
            'status' => 1,
            'last_ordering' => 6,
        ]);
    }

    protected function german() 
    {
        $german = Language::where('title', 'German')->first();

        // Admin Menu
        $adminMenu = Menu::where('name', 'Admin_' . $german->code)->first();

        $ordering = MenuLink::whereNull('parent_id')->where('menu_id', $adminMenu->id)->max('ordering');

        $adminMenu = MenuLink::create([
            'menu_id' => $adminMenu->id,
            'ordering' => $ordering + 1,
            'icon' => 'fas fa-images',
            'label' => utf8_encode('Fotos verwalten'),
            'link' => 'admin.photos.index',
            'status' => 1,
            'last_ordering' => 6,
        ]);
    }
}
