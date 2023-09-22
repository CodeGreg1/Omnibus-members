<?php

namespace Modules\Frontend\Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Modules\Menus\Support\MenuType;
use Modules\Languages\Models\Language;
use Illuminate\Database\Eloquent\Model;

class CreateFrontendMenusTableSeeder extends Seeder
{
    /**
     * @var array $primaryMenuLinks
     */
    protected $primaryMenuLinks = [
        [
            'parent_id' => null,
            'icon' => 'fas fa-home',
            'label' => 'Home',
            'link' => 'site.website.index',
            'last_ordering' => 1,
            'status' => 1,
        ],
        [
            'parent_id' => null,
            'icon' => 'fas fa-money',
            'label' => 'Pricing',
            'type' => 'Page',
            'link' => 'pricing',
            'last_ordering' => 2,
            'status' => 1,
        ],
        [
            'parent_id' => null,
            'icon' => 'fas fa-rss',
            'label' => 'Blogs',
            'link' => 'blogs.index',
            'last_ordering' => 3,
            'status' => 1,
        ],
        [
            'parent_id' => null,
            'icon' => 'fas fa-list',
            'label' => 'About',
            'type' => 'Page',
            'link' => 'about',
            'last_ordering' => 4,
            'status' => 1,
        ],
        [
            'parent_id' => null,
            'icon' => 'fas fa-list',
            'label' => 'FAQ',
            'type' => 'Page',
            'link' => 'faq',
            'last_ordering' => 5,
            'status' => 1,
        ],
        [
            'parent_id' => null,
            'icon' => 'fas fa-list',
            'label' => 'Contact',
            'type' => 'Page',
            'link' => 'contact',
            'last_ordering' => 6,
            'status' => 1,
        ]
    ];

    /**
     * @var array $quickLinks
     */
    protected $quickLinks = [
        [
            'parent_id' => null,
            'icon' => 'fas fa-right-to-bracket',
            'label' => 'Login',
            'link' => 'auth.login.show',
            'last_ordering' => 1,
            'status' => 1,
        ],
        [
            'parent_id' => null,
            'icon' => 'fas fa-right-from-bracket',
            'label' => 'Register',
            'link' => 'auth.register.show',
            'last_ordering' => 2,
            'status' => 1,
        ],
        [
            'parent_id' => null,
            'icon' => 'fas fa-right-from-bracket',
            'label' => 'Forgot Password',
            'link' => 'auth.register.show',
            'last_ordering' => 3,
            'status' => 1,
        ]
    ];

    /**
     * @var array $supportLinks
     */
    protected $supportLinks = [
        [
            'parent_id' => null,
            'icon' => 'fas fa-list',
            'label' => 'FAQ',
            'type' => 'Page',
            'link' => 'faq',
            'last_ordering' => 1,
            'status' => 1,
        ],
        [
            'parent_id' => null,
            'icon' => 'fas fa-list',
            'label' => 'Documentation',
            'link' => 'site.website.index',
            'last_ordering' => 2,
            'status' => 1,
        ],
        [
            'parent_id' => null,
            'icon' => 'fas fa-list',
            'label' => 'Contact',
            'type' => 'Page',
            'link' => 'contact',
            'last_ordering' => 3,
            'status' => 1,
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $english = Language::where('title', 'English')->first();

        $primaryMenu = Menu::create([
            'parent_id' => null,
            'language_id' => $english->id,
            'type' => MenuType::FRONTEND,
            'name' => 'Frontend Primary Menu',
            'description' => 'Frontend Primary Menu'
        ]);

        $this->storeMenuLinks(
            $primaryMenu,
            null,
            $this->primaryMenuLinks
        );

        $quickLinkMenu = Menu::create([
            'parent_id' => null,
            'language_id' => $english->id,
            'type' => MenuType::FRONTEND,
            'name' => 'Quick Link',
            'description' => 'Quick Link'
        ]);

        $this->storeMenuLinks(
            $quickLinkMenu,
            null,
            $this->quickLinks
        );

        $supportMenu = Menu::create([
            'parent_id' => null,
            'language_id' => $english->id,
            'type' => MenuType::FRONTEND,
            'name' => 'Support',
            'description' => 'Support'
        ]);

        $this->storeMenuLinks(
            $supportMenu,
            null,
            $this->supportLinks
        );
    }

    protected function storeMenuLinks($menu, $menuLink = null, $links = [])
    {
        if (is_null($menuLink)) {
            foreach ($links as $key => $link) {

                $menuLink = MenuLink::create(array_merge(
                    array_except(
                        $link,
                        'child'
                    ),
                    [
                        'menu_id' => $menu->id,
                        'ordering' => $key
                    ]
                ));

                if (isset($link['child'])) {
                    $this->storeMenuLinks(
                        $menu,
                        $menuLink,
                        $link['child']
                    );
                }
            }
        } else {
            //for children menu links
            foreach ($links as $key => $link) {
                MenuLink::create(array_merge(
                    $link,
                    [
                        'menu_id' => $menu->id,
                        'parent_id' => $menuLink->id,
                        'ordering' => $key
                    ]
                ));
            }
        }
    }
}
