<?php

namespace Modules\Photos\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Photos\Models\Photo;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PhotoDatatableController extends BaseController
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
        $this->authorize('admin.photos.datatable');

        return DataTables::eloquent(
            Media::with(['model'])->where('model_type', 'Modules\Photos\Models\Photo')
                ->when($request->get('queryValue'), function ($query, $search) {
                    $query->whereRelation(
                        'model',
                        'name',
                        'like',
                        '%' . $search . '%'
                    )->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('size', 'like', '%' . $search . '%');
                })
                ->when($request->get('folder'), function ($query, $folder) {
                    $query->whereIn('model_id', explode(',', $folder));
                })
        )
            ->addColumn('created', function ($row) {
                return $row->created_at->toUserTimezone()->toUserFormat();
            })
            ->addColumn('file_size', function ($row) {
                return $this->formatBytes($row->size);
            })
            ->addColumn('preview_url', function ($row) {
                if ($row->hasGeneratedConversion('preview')) {
                    return $row->preview_url;
                }
                return $row->original_url;
            })
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->toJson();
    }

    /**
     * Format bytes to kb, mb, gb, tb
     *
     * @param  integer $size
     * @param  integer $precision
     * @return integer
     */
    public static function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}
