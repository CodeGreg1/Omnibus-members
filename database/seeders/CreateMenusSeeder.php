<?php

namespace Database\Seeders;

use Modules\Menus\Models\Menu;
use Illuminate\Database\Seeder;
use Modules\Menus\Models\MenuLink;
use Modules\Menus\Support\MenuType;
use Modules\Languages\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateMenusSeeder extends Seeder
{
    /**
     * @var array $userLinks
     */
    protected $userLinks = [];

    /**
     * @var array $userLinksGerman
     */
    protected $userLinksGerman = [];

    /**
     * @var array $adminLinks
     */
    protected $adminLinks = [];

    /**
     * @var array $adminLinksGerman
     */
    protected $adminLinksGerman = [];

    public function __construct() 
    {
        $this->userLinks = [
            [
                'parent_id' => null,
                'icon' => 'fas fa-ticket-alt',
                'label' => 'Support Ticket',
                'link' => 'user.tickets.index',
                'last_ordering' => 6,
                'status' => 1
            ]
        ];

        $this->userLinksGerman = [
            [
                'parent_id' => null,
                'icon' => 'fas fa-ticket-alt',
                'label' => utf8_encode('Eintrittskarten'),
                'link' => 'user.tickets.index',
                'last_ordering' => 6,
                'status' => 1
            ]
        ];


        $this->adminLinks = [
            [
                'parent_id' => null,
                'icon' => 'fas fa-user',
                'label' => 'Manage Users',
                'link' => 'admin.users.index',
                'last_ordering' => 1,
                'status' => 1,
                'child' => [
                    [
                        'icon' => 'fas fa-user',
                        'label' => 'Users',
                        'link' => 'admin.users.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-user-shield',
                        'label' => 'Roles',
                        'link' => 'admin.roles.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-align-justify',
                        'label' => 'Permissions',
                        'link' => 'admin.permissions.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-history',
                        'label' => 'User Activities',
                        'link' => 'admin.activities.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ]
                ]
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-ticket-alt',
                'label' => 'Manage Tickets',
                'link' => 'admin.tickets.index',
                'last_ordering' => 8,
                'status' => 1
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-cog',
                'label' => 'Manage System',
                'link' => 'admin.settings.index',
                'last_ordering' => 9,
                'status' => 1,
                'child' => [
                    [
                        'icon' => 'fas fa-cog',
                        'label' => 'General Settings',
                        'link' => 'admin.settings.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-magic',
                        'label' => 'CRUD Generator',
                        'link' => 'admin.modules.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-align-left',
                        'label' => 'Menus',
                        'link' => 'admin.menus.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-language',
                        'label' => 'Languages',
                        'link' => 'admin.languages.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-gem',
                        'label' => 'Email Templates',
                        'link' => 'admin.email-templates.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-dollar-sign',
                        'label' => 'Currencies',
                        'link' => 'admin.available-currencies.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-list',
                        'label' => 'Category Types',
                        'link' => 'admin.category-types.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-list',
                        'label' => 'Categories',
                        'link' => 'admin.categories.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-list',
                        'label' => 'Coupons',
                        'link' => 'admin.coupons.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-user',
                        'label' => 'Shipping rates',
                        'link' => 'admin.shipping-rates.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-user',
                        'label' => 'Tax rates',
                        'link' => 'admin.tax-rates.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-toolbox',
                        'label' => 'Maintenance',
                        'link' => 'admin.maintenance.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-database',
                        'label' => 'Database Backup',
                        'link' => 'admin.database-backup.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-database',
                        'label' => 'Database Migration',
                        'link' => 'admin.migration.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-toolbox',
                        'label' => 'Application',
                        'link' => 'admin.system.info',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-toolbox',
                        'label' => 'Server',
                        'link' => 'admin.server.info',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-toolbox',
                        'label' => 'Cache',
                        'link' => 'admin.optimize.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ]
                ]
            ]
        ];

        $this->adminLinksGerman = [
            [
                'parent_id' => null,
                'icon' => 'fas fa-user',
                'label' => utf8_encode('Benutzer verwalten'),
                'link' => 'admin.users.index',
                'last_ordering' => 1,
                'status' => 1,
                'child' => [
                    [
                        'icon' => 'fas fa-user',
                        'label' => utf8_encode('Benutzer'),
                        'link' => 'admin.users.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-user-shield',
                        'label' => utf8_encode('Rollen'),
                        'link' => 'admin.roles.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-align-justify',
                        'label' => utf8_encode('Berechtigungen'),
                        'link' => 'admin.permissions.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-history',
                        'label' => 'Benutzeraktivit&auml;ten',
                        'link' => 'admin.activities.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ]
                ]
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-ticket-alt',
                'label' => utf8_encode('Tickets verwalten'),
                'link' => 'admin.tickets.index',
                'last_ordering' => 8,
                'status' => 1
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-cog',
                'label' => utf8_encode('System verwalten'),
                'link' => 'admin.settings.index',
                'last_ordering' => 9,
                'status' => 1,
                'child' => [
                    [
                        'icon' => 'fas fa-cog',
                        'label' => utf8_encode('Allgemeine Einstellungen'),
                        'link' => 'admin.settings.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-magic',
                        'label' => utf8_encode('CRUD-Generator'),
                        'link' => 'admin.modules.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-align-left',
                        'label' => 'Men&uuml;s',
                        'link' => 'admin.menus.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-language',
                        'label' => utf8_encode('Sprachen'),
                        'link' => 'admin.languages.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-gem',
                        'label' => utf8_encode('E-Mail-Vorlagen'),
                        'link' => 'admin.email-templates.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-dollar-sign',
                        'label' => 'W&auml;hrungen',
                        'link' => 'admin.available-currencies.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-list',
                        'label' => utf8_encode('Kategorietypen'),
                        'link' => 'admin.category-types.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-list',
                        'label' => utf8_encode('Kategorien'),
                        'link' => 'admin.categories.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-list',
                        'label' => utf8_encode('Gutscheine'),
                        'link' => 'admin.coupons.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-user',
                        'label' => utf8_encode('Versandpreise'),
                        'link' => 'admin.shipping-rates.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-user',
                        'label' => 'Steuers&auml;tze',
                        'link' => 'admin.tax-rates.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-toolbox',
                        'label' => utf8_encode('Wartung'),
                        'link' => 'admin.maintenance.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-database',
                        'label' => utf8_encode('Datenbanksicherung'),
                        'link' => 'admin.database-backup.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-database',
                        'label' => utf8_encode('Datenbankmigration'),
                        'link' => 'admin.migration.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-toolbox',
                        'label' => utf8_encode('Anwendung'),
                        'link' => 'admin.system.info',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-toolbox',
                        'label' => utf8_encode('Server'),
                        'link' => 'admin.server.info',
                        'last_ordering' => 0,
                        'status' => 1
                    ],
                    [
                        'icon' => 'fas fa-toolbox',
                        'label' => utf8_encode('Zwischenspeicher'),
                        'link' => 'admin.optimize.index',
                        'last_ordering' => 0,
                        'status' => 1
                    ]
                ]
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
        $english = Language::where('title', 'English')->first();

        $adminMenu = Menu::create([
            'parent_id' => null,
            'language_id' => $english->id,
            'type' => MenuType::ADMIN,
            'name' => 'Admin',
            'description' => 'Administrator menus.'
        ]);

        $this->storeMenuLinks(
            $adminMenu, 
            null, 
            $this->adminLinks
        );

        $german = Language::where('title', 'German')->first();

        $adminMenuGerman = Menu::create([
            'parent_id' => $adminMenu->id,
            'language_id' => $german->id,
            'type' => MenuType::ADMIN,
            'name' => 'Admin_' . $german->code,
            'description' => 'German Administrator menus.'
        ]);

        $this->storeMenuLinks(
            $adminMenuGerman, 
            null, 
            $this->adminLinksGerman
        );

        $userMenu = Menu::create([
            'parent_id' => null,
            'language_id' => $english->id,
            'type' => MenuType::USER,
            'name' => 'User',
            'description' => 'User menus.'
        ]);

        $this->storeMenuLinks(
            $userMenu, 
            null, 
            $this->userLinks
        );

        $userMenuGerman = Menu::create([
            'parent_id' => $userMenu->id,
            'language_id' => $german->id,
            'type' => MenuType::USER,
            'name' => 'User_' . $german->code,
            'description' => 'German User menus.'
        ]);

        $this->storeMenuLinks(
            $userMenuGerman, 
            null, 
            $this->userLinksGerman
        );
    }

    protected function storeMenuLinks($menu, $menuLink = null, $links = []) 
    {
        if(is_null($menuLink)) {
            foreach($links as $key => $link) {

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
                
                if(isset($link['child'])) {
                    $this->storeMenuLinks(
                        $menu, 
                        $menuLink, 
                        $link['child']
                    );
                }
            }
        } else {
            //for children menu links
            foreach($links as $key => $link) {
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
