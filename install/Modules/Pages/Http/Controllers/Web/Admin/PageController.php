<?php

namespace Modules\Pages\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Pages\States\Draft;
use Modules\Pages\States\Published;
use Modules\Pages\Events\PageCreated;
use Modules\Pages\Events\PageDeleted;
use Modules\Pages\Events\PageUpdated;
use Modules\Pages\Facades\PageBuilder;
use Illuminate\Contracts\Support\Renderable;
use Modules\Pages\Repositories\PagesRepository;
use Modules\Pages\Http\Requests\StorePageRequest;
use Modules\Pages\Http\Requests\UpdatePageRequest;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Pages\Http\Requests\DuplicatePageRequest;

class PageController extends BaseController
{
    /**
     * @var PagesRepository
     */
    protected $pages;

    protected $redirectTo = '/admin/pages';

    public function __construct(PagesRepository $pages)
    {
        parent::__construct();

        $this->pages = $pages;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.pages.index');

        return view('pages::admin.index', [
            'pageTitle' => __('Pages'),
            'types' => collect($this->pages->getModel()::TYPE_SELECT)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.pages.create');

        return view('pages::admin.create', [
            'pageTitle' => __('Create page'),
            'sections' => PageBuilder::getSections()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StorePageRequest $request
     * @return Renderable
     */
    public function store(StorePageRequest $request)
    {
        $page = $this->pages->create($request->only('name', 'description', 'type', 'slug', 'page_title', 'page_description', 'has_breadcrumb', 'dark_mode'));

        if ($page->type === 'section') {
            $sections = $request->get('sections');
            foreach ($sections as $section) {
                $section = (array) $section;
                $data = '{}';
                if (isset($section['data'])) {
                    $data = json_encode($section['data']);
                }
                $section['data'] = $data;
                $page->sections()->create($section);
            }
        } else {
            $page->content()->create([
                'body' => $request->get('content')
            ]);
        }

        event(new PageCreated($page));

        return $this->successResponse(__('Page created successfully.'), [
            'page' => $page
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.pages.edit');

        $page = $this->pages->getModel()->with([
            'sections' => function ($query) {
                $query->orderBy('order')->with(['backgroundImage.model']);
            }, 'content'
        ])->findOrFail($id);

        if ($page->type === 'section') {
            $page->sections = $page->sections->map(function ($section) {
                $conSection = PageBuilder::getSection($section->template);
                $section->name = $conSection ? $conSection->name : 'Section';
                $section->has_data = $conSection ? $conSection->has_data : true;
                return $section;
            });
        }

        return view('pages::admin.edit', [
            'pageTitle' => __('Edit page'),
            'sections' => PageBuilder::getSections(),
            'page' => $page,
            'default' => in_array($page->slug, config('pages.defaults'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdatePageRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdatePageRequest $request, $id)
    {
        $page = $this->pages->findOrFail($id);

        if (!in_array($page->slug, config('pages.defaults'))) {
            $attributes = $request->only('name', 'slug', 'description', 'page_title', 'page_description', 'has_breadcrumb', 'dark_mode');
        } else {
            $attributes = $request->only('name', 'description', 'page_title', 'page_description', 'has_breadcrumb', 'dark_mode');
        }

        $this->pages->update(
            $page,
            $attributes
        );

        if ($page->type === 'section') {
            $page->content()->delete();
            $sections = collect($request->get('sections'));
            $page->sections()->whereNotIn('id', $sections->pluck('id')->toArray())->delete();

            foreach ($sections as $section) {
                $section = (array) $section;
                $data = '{}';
                if (isset($section['data'])) {
                    $data = json_encode($section['data']);
                }
                $section['data'] = $data;
                $page->sections()->updateOrCreate(['id' => $section['id']], $section);
            }
        } else {
            $page->sections()->delete();

            if ($page->content) {
                $page->content()->update([
                    'body' => $request->get('content')
                ]);
            } else {
                $page->content()->create([
                    'body' => $request->get('content')
                ]);
            }
        }

        event(new PageUpdated($page->fresh()));

        return $this->handleAjaxRedirectResponse(
            __('Page updated successfully.'),
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
        $this->authorize('admin.pages.destroy');

        foreach ($request->pages as $id) {
            $page = $this->pages->findOrFail($id);

            if (!in_array($page->slug, config('pages.defaults')) || $page->parent_id) {
                $this->pages->delete($page);

                event(new PageDeleted($page));
            }
        }

        return $this->successResponse(__('Page successfully deleted'));
    }

    /**
     * Move the specified resource to draft status.
     * @param Request $request
     * @return Renderable
     */
    public function moveToDraft(Request $request)
    {
        $this->authorize('admin.pages.update');

        foreach ($request->pages as $id) {
            $page = $this->pages->findOrFail($id);

            try {
                $page->status->transitionTo(Draft::class);
                event(new PageUpdated($page));
            } catch (\Exception $e) {
                report($e);
            }
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
        $this->authorize('admin.pages.update');

        foreach ($request->pages as $id) {
            $page = $this->pages->findOrFail($id);
            try {
                $page->status->transitionTo(Published::class);
                event(new PageUpdated($page));
            } catch (\Exception $e) {
                report($e);
            }
        }

        return $this->successResponse(__('Successfully moved to published.'));
    }

    /**
     * Duplicate resource.
     * @param int $id
     * @param DuplicatePageRequest $request
     * @return Renderable
     */
    public function duplicate($id, DuplicatePageRequest $request)
    {
        $page = $this->pages->findOrFail($id);

        $newPage = $page->replicate();
        $newPage->slug = $request->get('slug');
        $newPage->name = $request->get('name');
        $newPage->description = $request->get('description');
        $newPage->status = 'draft';
        $newPage->save();

        if ($page->sections()->count()) {
            $page->sections()->get()->map(function ($section, $index) use ($newPage) {
                $sectionArray = $section->toArray();
                $data = '{}';
                if (isset($sectionArray['data'])) {
                    $data = json_encode((array)$sectionArray['data']);
                }
                $sectionArray['data'] = $data;
                $newPage->sections()->create($sectionArray);
            });
        }

        if ($page->content()->count()) {
            $newPage->content()->create($page->content()->first()->toArray());
        }

        if ($request->get('view_edit')) {
            return $this->handleAjaxRedirectResponse(
                __('Page successfully created.'),
                route('admin.pages.edit', $newPage)
            );
        }

        return $this->successResponse(__('Successfully created new page.'));
    }
}
