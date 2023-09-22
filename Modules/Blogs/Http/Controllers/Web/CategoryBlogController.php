<?php

namespace Modules\Blogs\Http\Controllers\Web;

use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\CategoryTypes\Models\CategoryType;
use Modules\Blogs\Repositories\BlogsRepository;
use Modules\Base\Http\Controllers\Web\SiteController;
use Modules\Categories\Repositories\CategoriesRepository;

class CategoryBlogController extends SiteController
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
    public function index(Request $request, $category)
    {
        $categoryName = str_replace("-", " ", $category);
        $category = $this->categories->getModel()
            ->whereRelation('category_type', 'type', 'Blog')
            ->where('name', 'LIKE', '%' . $categoryName . '%')->firstOrFail();

        $blogs = $this->blogs->getModel()
            ->where(['status' => 'published', 'category_id' => $category ? $category->id : 0])
            ->with(['category', 'author', 'thumbnail', 'thumbnail.model', 'tags'])
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
