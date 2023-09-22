<?php

namespace Modules\Pages\Http\Controllers\Web;

use \Share;
use Illuminate\Http\Request;
use Modules\Base\Support\JsPolicy;
use Illuminate\Contracts\Support\Renderable;
use Modules\Pages\Repositories\PagesRepository;
use Modules\Base\Http\Controllers\Web\SiteController;

class PagesController extends SiteController
{
    /**
     * @var PagesRepository
     */
    protected $pages;

    public function __construct(PagesRepository $pages)
    {
        parent::__construct();

        $this->pages = $pages;
    }

    /**
     * Show the specified resource.
     * @param string $slug
     * @return Renderable
     */
    public function show($slug)
    {
        $page = $this->pages->getModel()->with([
            'sections' => function ($query) {
                $query->orderBy('order')->with(['backgroundImage.model']);
            }, 'content'
        ])->where(['slug' => $slug, 'status' => 'published'])->firstOrFail();

        // get blog social sharer
        $socialShares = Share::page(url()->current(), $page->title)
            ->facebook()
            ->twitter()
            ->linkedin($page->title)
            ->telegram()
            ->getRawLinks();

        return view('pages::index', [
            'pageTitle' => $page->page_title,
            'policies' => JsPolicy::get('pages'),
            'socialShares' => $socialShares,
            'page' => $page
        ]);
    }

    public function preview($id)
    {
        $this->authorize('admin.pages.preview');

        $page = $this->pages->getModel()->with([
            'sections' => function ($query) {
                $query->orderBy('order')->with(['backgroundImage.model']);
            }, 'content'
        ])->findOrFail($id);

        // get blog social sharer
        $socialShares = Share::page(url()->current(), $page->title)
            ->facebook()
            ->twitter()
            ->linkedin($page->title)
            ->telegram()
            ->getRawLinks();

        return view('pages::index', [
            'pageTitle' => $page->name,
            'policies' => JsPolicy::get('pages'),
            'socialShares' => $socialShares,
            'page' => $page
        ]);
    }
}
