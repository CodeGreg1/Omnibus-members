<?php

namespace Modules\Blogs\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Contracts\Support\Renderable;
use Modules\Blogs\Repositories\BlogsRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class BlogDatatableController extends BaseController
{
    /**
     * @var BlogsRepository
     */
    protected $blogs;

    public function __construct(BlogsRepository $blogs)
    {
        parent::__construct();

        $this->blogs = $blogs;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle(Request $request)
    {
        $this->authorize('admin.blogs.datatable');

        return DataTables::eloquent(
            $this->blogs->getModel()
                ->with(['author', 'category'])
                ->when($request->get('status'), function ($query, $status) {
                    $query->when($status === 'Pending', function ($query) {
                        $query->where('status', 'pending');
                    })->when($status === 'Draft', function ($query) {
                        $query->where('status', 'draft');
                    })->when($status === 'Published', function ($query) {
                        $query->where('status', 'published');
                    });
                })
        )
            ->addColumn('created', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->addColumn('status_color', function ($row) {
                return $row->status->color();
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1])
                        ->orderBy('id', $sortValue[1]);
                }
            })
            ->toJson();
    }
}
