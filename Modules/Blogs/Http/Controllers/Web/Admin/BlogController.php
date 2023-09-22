<?php

namespace Modules\Blogs\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Blogs\Models\Blog;
use Modules\Blogs\States\Draft;
use Modules\Blogs\States\Pending;
use Modules\Blogs\States\Published;
use Modules\Blogs\Events\BlogCreated;
use Modules\Blogs\Events\BlogDeleted;
use Modules\Blogs\Events\BlogUpdated;
use Illuminate\Contracts\Support\Renderable;
use Modules\Blogs\Repositories\BlogsRepository;
use Modules\Blogs\Http\Requests\StoreBlogRequest;
use Modules\Blogs\Http\Requests\UpdateBlogRequest;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Categories\Repositories\CategoriesRepository;

class BlogController extends BaseController
{
    /**
     * @var BlogsRepository
     */
    protected $blogs;

    /**
     * @var CategoriesRepository
     */
    protected $categories;

    protected $redirectTo = '/admin/blogs';

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
    public function index()
    {
        $this->authorize('admin.blogs.index');

        return view('blogs::admin.index', [
            'pageTitle' => __('Blog posts'),
            'categories' => $this->categories->whereType('Blog')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.blogs.create');

        return view('blogs::admin.create', [
            'pageTitle' => __('New blog post'),
            'categories' => $this->categories->whereType('Blog'),
            'statuses' => Blog::getStatesFor('status')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreBlogRequest $request
     * @return Renderable
     */
    public function store(StoreBlogRequest $request)
    {
        $blog = $this->blogs->create($request->only('user_id', 'slug', 'title', 'description', 'media_id', 'category_id', 'content', 'status', 'page_title', 'page_description', 'modified_at'));

        foreach ($request->get('tags') as $value) {
            $blog->tags()->attach($value);
        }

        event(new BlogCreated($blog));

        return $this->handleAjaxRedirectResponse(
            __('Blog post created successfully.'),
            $this->redirectTo
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.blogs.edit');
        $blog = $this->blogs->getModel()->with(['thumbnail', 'tags'])->findOrFail($id);

        return view('blogs::admin.edit', [
            'pageTitle' => __('Edit blog post'),
            'categories' => $this->categories->whereType('Blog'),
            'statuses' => Blog::getStatesFor('status'),
            'blog' => $blog
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateBlogRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = $this->blogs->findOrFail($id);

        $this->blogs->update(
            $blog,
            $request->only('slug', 'title', 'description', 'media_id', 'category_id', 'content', 'page_title', 'page_description', 'modified_at')
        );

        $blog->tags()->detach();
        foreach ($request->get('tags') as $value) {
            $blog->tags()->attach($value);
        }

        event(new BlogUpdated($blog->fresh()));

        return $this->handleAjaxRedirectResponse(
            __('Blog post updated successfully.'),
            $this->redirectTo
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.blogs.destroy');

        foreach ($request->blogs as $id) {
            $blog = $this->blogs->findOrFail($id);

            $this->blogs->delete($blog);

            event(new BlogDeleted($blog));
        }

        return $this->successResponse(__('Blog post successfully deleted'));
    }

    /**
     * Move the specified resource to pending status.
     * @param Request $request
     * @return Renderable
     */
    public function moveToPending(Request $request)
    {
        $this->authorize('admin.blogs.update');

        foreach ($request->blogs as $id) {
            $blog = $this->blogs->findOrFail($id);

            $blog->status->transitionTo(Pending::class);

            event(new BlogUpdated($blog));
        }

        return $this->successResponse(__('Successfully moved to pending.'));
    }

    /**
     * Move the specified resource to draft status.
     * @param Request $request
     * @return Renderable
     */
    public function moveToDraft(Request $request)
    {
        $this->authorize('admin.blogs.update');

        foreach ($request->blogs as $id) {
            $blog = $this->blogs->findOrFail($id);

            $blog->status->transitionTo(Draft::class);
            event(new BlogUpdated($blog));
        }

        return $this->successResponse(__('Successfully moved to draft.'));
    }

    /**
     * Move the specified resource to published status.
     * @param Request $request
     * @return Renderable
     */
    public function moveToPublished(Request $request)
    {
        $this->authorize('admin.blogs.update');

        foreach ($request->blogs as $id) {
            $blog = $this->blogs->findOrFail($id);

            $blog->status->transitionTo(Published::class);
            event(new BlogUpdated($blog));
        }

        return $this->successResponse(__('Successfully moved to published.'));
    }
}
