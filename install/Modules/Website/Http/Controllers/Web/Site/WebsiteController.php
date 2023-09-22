<?php

namespace Modules\Website\Http\Controllers\Web\Site;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Support\JsPolicy;
use Illuminate\Support\Facades\Response;
use Modules\Subscriptions\Models\Feature;
use Illuminate\Contracts\Support\Renderable;
use Modules\Subscriptions\Models\PricingTable;
use Modules\Pages\Repositories\PagesRepository;
use Hexadog\ThemesManager\Facades\ThemesManager;
use Modules\Base\Http\Controllers\Web\SiteController;

class WebsiteController extends SiteController
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
     * Display a landing page
     * @return Renderable
     */
    public function index()
    {
        $page = $this->pages->getModel()->with([
            'sections' => function ($query) {
                $query->orderBy('order')->with(['backgroundImage.model']);
            }, 'content'
        ])->where('slug', 'home')->firstOrFail();

        return view('pages::index', [
            'pageTitle' => $page->page_title,
            'policies' => JsPolicy::get('pages'),
            'page' => $page
        ]);
    }

    /**
     * Display a about page
     * @return Renderable
     */
    public function about()
    {
        return view('website::site.about', [
            'pageTitle' => __('About')
        ]);
    }

    /**
     * Display a contact page
     * @return Renderable
     */
    public function contact()
    {
        return view('website::site.contact', [
            'pageTitle' => __('Contact')
        ]);
    }

    /**
     * Display a pricing page
     * @return Renderable
     */
    public function pricing()
    {
        $table = PricingTable::where('active', 1)->first();
        $prices = $table ? $table->table() : collect([]);
        $features = Feature::orderBy('ordering', 'asc')->get();

        return view('website::site.pricing', [
            'pageTitle' => __('Our pricing'),
            'table' => $table,
            'prices' => $prices,
            'features' => $features
        ]);
    }

     /**
     * Set user cookie consent
     * @param Request $request
     * @return JsonResponse
     */
    public function cookieConsent(Request $request)
    {
        return $this->setCookieResponse(
            __('Success'),
            'cookie-consent', 
            true,
            (setting('frontend_cookie_duration') * 1440)
        );
    }
}
