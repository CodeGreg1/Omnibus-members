<?php

namespace Modules\Pages\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Pages\Repositories\PagesRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class PageDatatableController extends BaseController
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
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle(Request $request)
    {
        $this->authorize('admin.pages.datatable');

        return DataTables::eloquent(
            $this->pages->getModel()
                ->when($request->get('status'), function ($query, $status) {
                    $query->when($status === 'Draft', function ($query) {
                        $query->where('status', 'draft');
                    })->when($status === 'Published', function ($query) {
                        $query->where('status', 'published');
                    });
                })
        )
            ->addColumn('created', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->addColumn('type_label', function ($row) {
                return $this->pages->getModel()::TYPE_SELECT[$row->type];
            })
            ->addColumn('status_color', function ($row) {
                return $row->status->color();
            })
            ->addColumn('default', function ($row) {
                return in_array($row->slug, config('pages.defaults'));
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->toJson();
    }
}
