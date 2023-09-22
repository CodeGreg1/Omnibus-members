<?php

namespace Modules\Pages\Database\Seeders;

use Modules\Pages\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CreateContactUsPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $page = Page::create([
            'name' => 'Contact Us',
            'description' => 'Contact Us page',
            'type' => 'section',
            'slug' => 'contact',
            'status' => 'published',
            'page_title' => 'Contact Us',
            'page_description' => 'Contact Us',
            'has_breadcrumb' => 1
        ]);

        $array = $this->getSections();

        foreach ($array as $key => $value) {
            $section = json_decode(json_encode($value), true);
            $section['order'] = $key + 1;
            $page->sections()->create($section);
        }
    }

    protected function getSections()
    {
        $sections = '[{"id":18,"order":1,"page_id":4,"heading":"Contact Us","sub_heading":"","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!","background_color":"white","media_id":"","template":"contact_us","data":{},"created_at":"2022-12-26T04:47:19.000000Z","updated_at":"2022-12-26T04:47:19.000000Z","deleted_at":null}]';

        return json_decode($sections);
    }
}
