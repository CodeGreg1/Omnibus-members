<?php

namespace Modules\Menus\Http\Controllers\Web\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Blogs\Models\Blog;
use Modules\Pages\Models\Page;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Support\Route\RouteNameParser;
use Modules\Base\Http\Controllers\Web\BaseController;

class MenuLinkSelectListController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle(Request $request)
    {
        $limit = $request->get('limit') ?? 25;
        if ($request->type === 'Blog') {
            $result =  Blog::where('status', 'published')
                ->when($request->get('search'), function ($query, $search) {
                    $query->where('title', 'LIKE', '%' . $search . '%');
                })->paginate($limit);

            $result->getCollection()->transform(function ($item) {
                return (object) [
                    'id' => $item->slug,
                    'text' => $item->title
                ];
            });
        } else if ($request->type === 'Page') {
            $result =  Page::where('status', 'published')
                ->when($request->get('search'), function ($query, $search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })->paginate($limit);

            $result->getCollection()->transform(function ($item) {
                return (object) [
                    'id' => $item->slug,
                    'text' => $item->name
                ];
            });
        } else {
            $result = collect(app()->routes->getRoutes())
                ->filter(function ($item) {
                    return $item->getName();
                })
                ->map(function ($item) {
                    $routeName = $item->getName();
                    $text = (new RouteNameParser)->parse($routeName) . ' (' . $routeName . ')';

                    return (object) [
                        'id' => $routeName,
                        'text' => $text
                    ];
                })->when($request->get('search'), function ($collection, $search) {
                    return $collection->filter(function ($item) use ($search) {
                        return Str::contains($item->text, $search);
                    });
                })->values()->paginate($limit, $request->get('page') ?? 1);
        }

        return [
            'results' => $result->items(),
            'pagination' => [
                'more' => $result->hasMorePages()
            ]
        ];
    }
}
