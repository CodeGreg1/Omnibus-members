<?php

namespace Modules\Blogs\Http\Controllers\Web;

use \Share;
use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\CategoryTypes\Models\CategoryType;
use Modules\Blogs\Repositories\BlogsRepository;
use Modules\Base\Http\Controllers\Web\SiteController;
use Modules\Categories\Repositories\CategoriesRepository;

class BlogsController extends SiteController
{
    /**
     * @var BlogsRepository
     */
    protected $blogs;

    /**
     * @var CategoriesRepository
     */
    protected $categories;

    public function __construct(
        BlogsRepository $blogs,
        CategoriesRepository $categories
    ) {
        parent::__construct();

        $this->blogs = $blogs;
        $this->categories = $categories;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $blogs = $this->blogs->getModel()
            ->where('status', 'published')
            ->with(['category', 'author', 'thumbnail', 'thumbnail.model', 'tags'])
            ->when($request->get('category'), function ($query, $category) {
                if ($category !== 'all') {
                    $query->where('category_id', $category);
                }
            })
            ->orderBy('blogs.id', 'desc')
            ->paginate(10)->appends(request()->query());

        return view('blogs::index', [
            'pageTitle' => config('blogs.name'),
            'policies' => JsPolicy::get('blogs'),
            'categories' => $this->categories->whereType(CategoryType::TYPE_BLOG),
            'blogs' =>  $blogs,
            'popular' => $this->blogs->popular(5),
            'latest' => $this->blogs->latest(5)
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($slug)
    {
        $blog = $this->blogs->getModel()
            ->where([
                'slug' => $slug,
                'status' => 'published'
            ])
            ->with(['category', 'author', 'thumbnail', 'thumbnail.model', 'tags'])
            ->firstOrFail();

        $blog->increment('views');

        // get previous blog
        $previous = $this->blogs->getModel()->with(['author', 'thumbnail', 'thumbnail.model'])->where('id', '<', $blog->id)->where('status', 'published')->first();

        // get next blog
        $next = $this->blogs->getModel()->with(['author', 'thumbnail', 'thumbnail.model'])->where('id', '>', $blog->id)->where('status', 'published')->first();

        // get blog social sharer
        $socialShares = Share::page(url()->current(), $blog->title)
            ->facebook()
            ->twitter()
            ->linkedin($blog->title)
            ->telegram()
            ->getRawLinks();

        return view('blogs::show', [
            'pageTitle' => $blog->title,
            'policies' => JsPolicy::get('blogs'),
            'blog' => $blog,
            'categories' => $this->categories->whereType(CategoryType::TYPE_BLOG),
            'popular' => $this->blogs->popular(5, $blog),
            'latest' => $this->blogs->latest(5, $blog),
            'previous' => $previous,
            'next' => $next,
            'socialShares' => $socialShares
        ]);
    }
}
