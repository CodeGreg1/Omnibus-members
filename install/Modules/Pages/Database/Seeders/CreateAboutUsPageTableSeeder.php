<?php

namespace Modules\Pages\Database\Seeders;

use Modules\Pages\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CreateAboutUsPageTableSeeder extends Seeder
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
            'name' => 'About Us',
            'description' => 'About Us page',
            'type' => 'section',
            'slug' => 'about',
            'status' => 'published',
            'page_title' => 'About Us',
            'page_description' => 'About Us',
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
        $sections = '[{"id":16,"order":1,"page_id":3,"heading":"Meet our team","sub_heading":"","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt, fugiat vitae doloribus repudiandae eos soluta unde voluptate architecto aperiam!","background_color":"white","media_id":"","template":"team","data":"{\"items\":[{\"id\":\"161637107183064450\",\"avatar\":\"\\\/upload\\\/media\\\/placeholders\\\/100x100.png\",\"name\":\"Terry Medhurst\",\"position\":\"CEO\",\"description\":\"Excellent feature! I love it. One day I\'m definitely going to put this Bootstrap component into use and I\'ll let you know once I do.\",\"facebook\":\"https:\\/\\/www.facebook.com\\/terry\",\"twitter\":\"https:\\/\\/www.twitter.com\\/terry\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/terry\",\"dribbble\":\"https:\\/\\/www.dribbble.com\\/terry\"},{\"id\":\"2368832736583168\",\"avatar\":\"\\\/upload\\\/media\\\/placeholders\\\/100x100.png\",\"name\":\"Alison Reichert\",\"position\":\"President\",\"description\":\"Excellent feature! I love it. One day I\'m definitely going to put this Bootstrap component into use and I\'ll let you know once I do.\",\"facebook\":\"https:\\/\\/www.facebook.com\\/terry\",\"twitter\":\"https:\\/\\/www.twitter.com\\/terry\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/terry\",\"dribbble\":\"https:\\/\\/www.dribbble.com\\/terry\"},{\"id\":\"530643403690104400\",\"avatar\":\"\\\/upload\\\/media\\\/placeholders\\\/100x100.png\",\"name\":\"Ewell Mueller\",\"position\":\"Manager\",\"description\":\"Excellent feature! I love it. One day I\'m definitely going to put this Bootstrap component into use and I\'ll let you know once I do.\",\"facebook\":\"https:\\/\\/www.facebook.com\\/terry\",\"twitter\":\"https:\\/\\/www.twitter.com\\/terry\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/terry\",\"dribbble\":\"https:\\/\\/www.dribbble.com\\/terry\"},{\"id\":\"747132917338021900\",\"avatar\":\"\\\/upload\\\/media\\\/placeholders\\\/100x100.png\",\"name\":\"Jeanne Halvorson\",\"position\":\"Developer\",\"description\":\"Excellent feature! I love it. One day I\'m definitely going to put this Bootstrap component into use and I\'ll let you know once I do.\",\"facebook\":\"https:\\/\\/www.facebook.com\\/terry\",\"twitter\":\"https:\\/\\/www.twitter.com\\/terry\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/terry\",\"dribbble\":\"https:\\/\\/www.dribbble.com\\/terry\"}]}","created_at":"2022-12-26T04:42:55.000000Z","updated_at":"2022-12-26T04:42:55.000000Z","deleted_at":null},{"id":17,"order":2,"page_id":3,"heading":"Who we are","sub_heading":"","description":"Compellingly actualize excellent users and distinctive leadership skills. Interactively productivate cross functional methodologies with visionary e-business. Appropriately generate diverse \"outside the box\" thinking whereas cutting-edge deliverables.","background_color":"white","media_id":"","template":"about_us","data":"{\"label\":\"\",\"link\":\"\",\"new_tab\":1,\"primary\":0}","created_at":"2022-12-26T04:42:55.000000Z","updated_at":"2022-12-26T04:42:55.000000Z","deleted_at":null}]';

        return json_decode($sections);
    }
}
