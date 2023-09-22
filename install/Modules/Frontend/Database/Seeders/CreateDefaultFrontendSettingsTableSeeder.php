<?php

namespace Modules\Frontend\Database\Seeders;

use Setting;
use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CreateDefaultFrontendSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $primaryMenu = Menu::where([
            'type' => 'Frontend',
            'name' => 'Frontend Primary Menu'
        ])->first();
        if ($primaryMenu) {
            Setting::set('frontend_primary_menu', $primaryMenu->id);
        }

        $menu1 = Menu::where([
            'type' => 'Frontend',
            'name' => 'Quick Link'
        ])->first();
        if ($menu1) {
            Setting::set('frontend_footer_menu1_title', 'Quick Link');
            Setting::set('frontend_footer_menu1', $menu1->id);
        }

        $menu2 = Menu::where([
            'type' => 'Frontend',
            'name' => 'Support'
        ])->first();
        if ($menu2) {
            Setting::set('frontend_footer_menu2_title', 'Support');
            Setting::set('frontend_footer_menu2', $menu2->id);
        }

        Setting::save();
    }
}
