<?php

namespace Modules\Sitemap\Console;

use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Modules\Tags\Models\Tag;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Carbon;
use Modules\Blogs\Models\Blog;
use Modules\Pages\Models\Page;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Modules\Categories\Models\Category;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cenerate sitemap for all pages.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->clear();

        $sitemap = Sitemap::create();

        $frequency = setting('sitemap_change_frequency', 'daily');
        $priorityLevel = setting('sitemap_priority_level', 0.1);

        // Main
        Sitemap::create()
            ->add(Url::create('/')
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency($frequency)
                ->setPriority($priorityLevel))
            ->writeToFile(public_path('main_sitemap.xml'));


        $sitemap->add(Url::create('main_sitemap.xml')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency($frequency)
            ->setPriority($priorityLevel));

        // Blogs
        Blog::where('status', 'published')
            ->chunk(100, function ($blogs, $i) use ($sitemap, $frequency, $priorityLevel) {
                $blogSitemap = Sitemap::create();
                foreach ($blogs as $blog) {
                    $blogSitemap->add(Url::create(route('blogs.show', $blog->slug))
                        ->setLastModificationDate(Carbon::create($blog->modified_at))
                        ->setChangeFrequency($frequency)
                        ->setPriority($priorityLevel));
                }

                $url = '/blogs_sitemap_page_' . $i . '.xml';
                $blogSitemap->writeToFile(public_path($url));
                $sitemap->add(Url::create($url)
                    ->setLastModificationDate(Carbon::yesterday())
                    ->setChangeFrequency($frequency)
                    ->setPriority($priorityLevel));
            });

        // Pages
        Page::where('status', 'published')
            ->where('slug', '!=', 'home')
            ->chunk(100, function ($pages, $i) use ($sitemap, $frequency, $priorityLevel) {
                $pageSitemap = Sitemap::create();
                foreach ($pages as $page) {
                    $pageSitemap->add(Url::create(route('pages.show', $page->slug))
                        ->setLastModificationDate(Carbon::create($page->updated_at))
                        ->setChangeFrequency($frequency)
                        ->setPriority($priorityLevel));
                }

                $url = '/pages_sitemap_page_' . $i . '.xml';
                $pageSitemap->writeToFile(public_path($url));
                $sitemap->add(Url::create($url)
                    ->setLastModificationDate(Carbon::yesterday())
                    ->setChangeFrequency($frequency)
                    ->setPriority($priorityLevel));
            });

        // Categories
        Category::chunk(100, function ($categories, $i) use ($sitemap, $frequency, $priorityLevel) {
            $categorySitemap = Sitemap::create();
            foreach ($categories as $category) {
                $categorySitemap->add(Url::create(
                    route('blogs.categories.index', Str::slug($category->name))
                )
                    ->setLastModificationDate(Carbon::create($category->updated_at))
                    ->setChangeFrequency($frequency)
                    ->setPriority($priorityLevel));
            }

            $url = '/categories_sitemap_page_' . $i . '.xml';
            $categorySitemap->writeToFile(public_path($url));
            $sitemap->add(Url::create($url)
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency($frequency)
                ->setPriority($priorityLevel));
        });

        // Tags
        Tag::chunk(100, function ($tags, $i) use ($sitemap, $frequency, $priorityLevel) {
            $tagSitemap = Sitemap::create();
            foreach ($tags as $tag) {
                $tagSitemap->add(Url::create(
                    route('blogs.tags.index', Str::slug($tag->name))
                )
                    ->setLastModificationDate(Carbon::create($tag->updated_at))
                    ->setChangeFrequency($frequency)
                    ->setPriority($priorityLevel));
            }

            $url = '/tags_sitemap_page_' . $i . '.xml';
            $tagSitemap->writeToFile(public_path($url));
            $sitemap->add(Url::create($url)
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency($frequency)
                ->setPriority($priorityLevel));
        });

        // Write sitemap file
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Created');
    }

    protected function clear()
    {
        $files = glob('public/*');
        foreach ($files as $file) {
            if (Str::contains($file, '.xml')) {
                File::delete($file);
            }
        }
    }
}
