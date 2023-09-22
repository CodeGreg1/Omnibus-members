<?php

namespace Modules\Pages\Services;

use Modules\Pages\Services\Sections\CTA;
use Modules\Pages\Services\Sections\Faq;
use Modules\Pages\Services\Sections\Blog;
use Modules\Pages\Services\Sections\Hero;
use Modules\Pages\Services\Sections\Team;
use Modules\Pages\Services\Sections\Boxed;
use Modules\Pages\Services\Sections\Client;
use Modules\Pages\Services\Sections\AboutUs;
use Modules\Pages\Services\Sections\Pricing;
use Modules\Pages\Services\Sections\ContactUs;
use Modules\Pages\Services\Sections\TextMedia;
use Modules\Pages\Services\Sections\Statistics;
use Modules\Pages\Services\Sections\RecentWorks;
use Modules\Pages\Services\Sections\Testimonial;
use Modules\Pages\Services\Sections\BoxedLeftIcon;

class SectionBuilderFactory extends AbstractSectionBuilderFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'hero' => Hero::class,
        'about_us' => AboutUs::class,
        'statistics' => Statistics::class,
        'recent_works' => RecentWorks::class,
        'testimonial' => Testimonial::class,
        'boxed' => Boxed::class,
        'boxed_left_icon' => BoxedLeftIcon::class,
        'team' => Team::class,
        'client' => Client::class,
        'pricing' => Pricing::class,
        'faq' => Faq::class,
        'blog' => Blog::class,
        'cta' => CTA::class,
        'contact_us' => ContactUs::class,
        'text_media' => TextMedia::class
    ];

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}
