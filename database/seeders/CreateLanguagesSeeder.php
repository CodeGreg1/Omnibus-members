<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Models\Country;
use Modules\Languages\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateLanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $english = Country::where('name', 'United States')->first();

        Language::create([
            'title' => 'English',
            'code' => 'en',
            'direction' => 'ltr',
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'flag_id' => $english->id
        ]);

        $german = Country::where('name', 'Germany')->first();

        Language::create([
            'title' => 'German',
            'code' => 'de',
            'direction' => 'ltr',
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'flag_id' => $german->id
        ]);
    }
}
