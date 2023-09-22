<?php

return [
    'name' => __('Pages'),

    'defaults' => [
        'home',
        'about',
        'contact',
        'pricing',
        'faq',
        'privacy-policy',
        'terms-and-conditions'
    ],

    'sections' => [
        [
            'name' => __('Hero'),
            'template' => 'hero',
            'image' => '/upload/media/icon/hero.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\Hero::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\Hero::class
            ],
            'default' => true
        ],
        [
            'name' => __('About us'),
            'template' => 'about_us',
            'image' => '/upload/media/icon/about_us.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\AboutUs::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\AboutUs::class
            ],
            'default' => true
        ],
        [
            'name' => __('Statistics'),
            'template' => 'statistics',
            'image' => '/upload/media/icon/statistics.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\Statistics::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\Statistics::class
            ],
            'default' => true
        ],
        [
            'name' => __('Recent works'),
            'template' => 'recent_works',
            'image' => '/upload/media/icon/recent_works.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\RecentWorks::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\RecentWorks::class
            ],
            'default' => true
        ],
        [
            'name' => __('Testimonial'),
            'template' => 'testimonial',
            'image' => '/upload/media/icon/testimonial.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\Testimonial::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\Testimonial::class
            ],
            'default' => true
        ],
        [
            'name' => __('Boxed content'),
            'template' => 'boxed',
            'image' => '/upload/media/icon/grid.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\Boxed::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\Boxed::class
            ],
            'default' => true
        ],
        [
            'name' => __('Boxed left icon'),
            'template' => 'boxed_left_icon',
            'image' => '/upload/media/icon/grid.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\BoxedLeftIcon::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\BoxedLeftIcon::class
            ],
            'default' => true
        ],
        [
            'name' => __('Text media'),
            'template' => 'text_media',
            'image' => '/upload/media/icon/text_media.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\TextMedia::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\TextMedia::class
            ],
            'default' => true
        ],
        [
            'name' => __('Team'),
            'template' => 'team',
            'image' => '/upload/media/icon/team.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\Team::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\Team::class
            ],
            'default' => true
        ],
        [
            'name' => __('Client'),
            'template' => 'client',
            'image' => '/upload/media/icon/client.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\Client::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\Client::class
            ],
            'default' => true
        ],
        [
            'name' => __('Faq'),
            'template' => 'faq',
            'image' => '/upload/media/icon/faq.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\Faq::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\Faq::class
            ],
            'default' => true
        ],
        [
            'name' => __('CTA'),
            'template' => 'cta',
            'image' => '/upload/media/icon/cta.svg',
            'has_data' => true,
            'builder_class' => Modules\Pages\Services\Sections\CTA::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\CTA::class
            ],
            'default' => true
        ],
        [
            'name' => __('Contact Us'),
            'template' => 'contact_us',
            'image' => '/upload/media/icon/form.svg',
            'has_data' => false,
            'builder_class' => Modules\Pages\Services\Sections\ContactUs::class,
            'validators' => [
                Modules\Pages\Support\SectionValidator\ContactUs::class
            ],
            'default' => true
        ]
    ]
];