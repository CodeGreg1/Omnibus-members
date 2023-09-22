<?php

namespace Modules\Blogs\Http\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Tags\Repositories\TagsRepository;
use Modules\CategoryTypes\Models\CategoryType;
use Modules\Blogs\Repositories\BlogsRepository;
use Modules\Base\Http\Controllers\Web\SiteController;
use Modules\Categories\Repositories\CategoriesRepository;

class TagBlogController extends SiteController
{
    /**
     * @var BlogsRepository
     */
    protected $blogs;

    /**
     * @var CategoriesRepository
     */
    protected $categories;

    /**
     * @var TagsRepository
     */
    protected $tags;

    public function __construct(
        BlogsRepository $blogs,
        CategoriesRepository $categories,
        TagsRepository $tags
    ) {
        parent::__construct();

        $this->blogs = $blogs;
        $this->categories = $categories;
        $this->tags = $tags;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, $tag)
    {
        $tagName = str_replace("-", " ", $tag);
        $tag = $this->tags->where('name', 'LIKE', '%' . $tagName . '%')->firstOrFail();

        $blogs = $this->blogs->getModel()
            ->where('status', 'published')
            ->with(['category', 'author', 'thumbnail', 'thumbnail.model', 'tags'])
            ->whereHas('tags', function ($query)  use ($tag) {
                $query->where('tags.id', $tag ? $tag->id : 0);
            })
            ->orderBy('blogs.id', 'desc')
            ->paginate(12)->appends(request()->query());

        return view('blogs::index', [
            'pageTitle' => config('blogs.name'),
            'policies' => JsPolicy::get('blogs'),
            'categories' => $this->categories->whereType(CategoryType::TYPE_BLOG),
            'blogs' =>  $blogs,
            'popular' => $this->blogs->popular(8),
            'latest' => $this->blogs->latest(8)
        ]);
    }
}
